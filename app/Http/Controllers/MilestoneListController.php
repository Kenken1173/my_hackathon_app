<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// ゴールを選択し、マイルストーンの一覧を表示する画面
class MilestoneListController extends Controller
{
    private static function loadJson() {
        return json_decode(file_get_contents(storage_path("dataStore.json")), true)["goals"];
    }
    private static function updateJson($json) {
        // TODO 今後やるかも？
    }
    public function get($goal_id)
    {
        return view("milestoneList", ["goal" => $this::getGoal($goal_id)]);
    }
    private static function getGoal($goal_id)
    {
        $goals = MilestoneListController::loadJson();
        foreach ($goals as $goal) {
            if ($goal["id"] == $goal_id) {
                return $goal;
            }
        }
        return null; // TODO 何を返すと適切？
    }
}