<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Goal;
use App\Models\Milestone;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpParser\Node\NullableType;


// ユーザーのゴールの一覧画面
class UserGoalListController extends Controller
{
    private function findOneBy($id)
    {
        $goal = Goal::find($id);
        return $goal;
    }
    public function index()
    {
        $goals_with_milestones = array();
        $user = User::find(4); // TODO 定数ではなくする
        $goals = Goal::where("user_id", $user->id)->get();
        $today_full_count = 0;
        $today_achieved_count = 0;
        $thisMonth_goal_count = 0;
        $thisMonth_milestone_count = 0;
        foreach ($goals as $goal) {
            $milestones = Milestone::where("goal_id", $goal->id)->get();
            // DD($milestones);
            $full_count = count($milestones);
            $achieved_count = 0;

            $current_count = 0;
            $yet_count = 0;

            foreach ($milestones as $milestone) {
                if ($milestone->achieved) {
                    $achieved_count++;
                }
                $milestone_startDate = Carbon::parse($milestone->startDate);
                $milestone_endDate = Carbon::parse($milestone->endDate);
                if (Carbon::today()->isBetween($milestone_startDate, $milestone_endDate) && !$milestone->achieved)
                    $current_count++;
                else if (Carbon::today()->isBefore($milestone_startDate) && !$milestone->achieved)
                    $yet_count++;
                if ($milestone_endDate->isToday()) {
                    $today_full_count++;
                    if ($milestone->achieved) {
                        $today_achieved_count++;
                    }
                }
                if ($milestone_endDate->isBetween($milestone_endDate->startOfMonth(), $milestone_endDate->endOfMonth())) {
                    if ($milestone->achieved)
                        $thisMonth_milestone_count++;
                }
            }
            if (!is_null($milestones->last())) {
                $goal_endDate = Carbon::parse($milestones->last()->endDate);
                $remain_days = Carbon::today()->diffInDays($goal_endDate);
                array_push($goals_with_milestones, array(
                    "goal" => $goal,
                    "milestones" => $milestones,
                    "full_count" => $full_count,
                    "achieved_count" => $achieved_count,
                    "current_count" => $current_count,
                    "yet_count" => $yet_count,
                    "end_date" => $goal_endDate,
                    "remain_days" => $remain_days,
                ));
                if ($goal_endDate->isBetween($goal_endDate->startOfMonth(), $goal_endDate->endOfMonth()))
                    if ($milestones->last()->achieved)
                        $thisMonth_goal_count++;
            }
        }
        if ($today_achieved_count == 0) {
            $today_achieved_persent = 0;
        } else {
            $today_achieved_persent = $today_achieved_count / $today_full_count * 100;

        }
        return view("welcome", [
            'goals_with_milestones' => $goals_with_milestones,
            "today_full_count" => $today_full_count,
            "today_achieved_count" => $today_achieved_count,
            "today_achieved_persent" => $today_achieved_persent,
            "thisMonth_goal_count" => $thisMonth_goal_count,
            "thisMonth_milestone_count" => $thisMonth_milestone_count,
            "username" => $user->name
        ]);
    }
    public function get($id)
    {
        return $this->findOneBy($id);
    }
}