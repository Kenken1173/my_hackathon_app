<?php

namespace App\Http\Controllers;

use App\Models\Memo;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    private static function loadJson() {
        return json_decode(file_get_contents(storage_path("dataStore.json")), true)["goals"];
    }
    private static function updateJson($json) {
        # TODO 今後やるかも？
    }
    // フォーム表示
    public function index()
    {
        $goals = GoalController::loadJson();
        return $goals;
    }
    public function get($id)
    {
        $goals = GoalController::loadJson();
        foreach ($goals as $goal) {
            if ($goal["id"] == $id) {
                return $goal;
            }
        }
        return null;
    }
}