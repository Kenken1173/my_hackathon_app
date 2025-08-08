<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use App\Models\Milestone;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpParser\Node\NullableType;

class MilestonesWBSController extends Controller
{
    public function get($goal_id)
    {
        $user = User::find(1); // TODO 定数ではなくする
        // $goal = Goal::where("user_id", $user->id)->where("id", $goal_id)->get(); // こっちのほうが適切
        $goal = Goal::where("id", $goal_id)->first();
        $milestones = Milestone::where("goal_id", $goal->id)
            ->orderBy('startDate')
            ->get();

        $startDate = $milestones->isNotEmpty() ? Carbon::parse($milestones->min('startDate')) : null;
        $endDate = $milestones->isNotEmpty() ? Carbon::parse($milestones->max('endDate')) : null;
        return view("milestonesWBS", [
            "goal" => $goal,
            "milestones" => $milestones,
            "username" => $user->name,
            "startDate" => $startDate,
            "endDate" => $endDate
        ]);
    }
}