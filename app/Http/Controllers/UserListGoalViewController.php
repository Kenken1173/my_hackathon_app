<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpParser\Node\NullableType;


// ユーザーのゴールの一覧画面
class UserListGoalViewController extends Controller
{
    private static function loadJson() {
        return json_decode(file_get_contents(storage_path("dataStore.json")), true)["goals"];
    }
    public function index()
    {
        $goals = $this::loadJson();
        $user = UserController::loadJson()[0];
        $result = array();
        foreach ($goals as $goal) {
            if ($goal["userId"] == $user["id"]) {
                array_push($result, $goal);
            }
        }
        return view("index", ['goals' => $result]);
    }
}