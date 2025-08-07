<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpParser\Node\NullableType;

class MilestonesWBSController extends Controller
{
    private static function loadJson() {
        return json_decode(file_get_contents(storage_path("dataStore.json")), true)["goals"];
    }

    public function get($goal_id)
    {
        $goal = $this::getGoal($goal_id);
        $maxEndDate = null;
        if (isset($goal['milestones']) && is_array($goal['milestones'])) {
            $maxEndDate = collect($goal['milestones'])->max('endDate');
        }
        return view("milestonesWBS", [
            "goal" => $goal,
            "username" => $this::getGoalUser($goal_id)["name"],
            "maxEndDate" => $maxEndDate
        ]);
    }

    public static function getGoalUser($goal_id){
        $goal = MilestonesWBSController::getGoal($goal_id);
        $user_id = $goal['userId'];
        $users = json_decode(file_get_contents(storage_path("dataStore.json")), true)["users"];
        foreach ($users as $user) {
            if ($user["id"] == $user_id) {
                return $user;
            }
        }
        return null;
    }   

    private static function getGoal($goal_id)
    {
        $goals = MilestonesWBSController::loadJson();
        // TODO goal一つだけを取得
        foreach ($goals as $goal) {
            if ($goal["id"] == $goal_id) {
                return $goal;
            }
        }
        return null;
    }
}