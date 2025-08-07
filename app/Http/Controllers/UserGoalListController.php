<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use App\Models\Milestone;
use Illuminate\Http\Request;
use PhpParser\Node\NullableType;


// ユーザーのゴールの一覧画面
class UserGoalListController extends Controller
{
    private static function loadJson() {
        return json_decode(file_get_contents(storage_path("dataStore.json")), true)["goals"];
    }

    private function findOneBy($id)
    {
        $goal = Goal::find($id);
        return $goal;
    }
    public function index()
    {
        $goals_with_milestone = array();
        // $user = UserController::loadJson()[2];
        $goals = Goal::where("user_id", 2)->get();
        foreach ($goals as $goal)
        {
            $milestones = Milestone::where("goal_id", $goal->id)->get();
            $full_count = count($milestones);
            $achieved_count = 0;
            foreach ($milestones as $milestone) {
                if ($milestone->achieved) {
                    $achieved_count++;
                }
            }
            array_push($goals_with_milestone, array($goal, $full_count, $achieved_count));
        }
        // DD($goals);
        // $result = array();
        // foreach ($goals as $goal) {
        //     if ($goal["userId"] == $user["id"]) {
        //         array_push($result, $goal);
        //     }
        // }
        // var_dump($goals);
        return view("welcome", ['goals_with_milestone' => $goals_with_milestone, "username" => "dummy"]);
    }
    public function get($id)
    {
        return $this->findOneBy($id);
    }
}