<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// ユーザーの新しい目標・現状・期日入力画面
class NewgoalController extends Controller
{
    private static function loadJson() {
        return json_decode(file_get_contents(storage_path("dataStore.json")), true)["goals"];
    }
    public function index()
    {
        $user = UserController::loadJson()[0];
        return view("newGoal",["username" => $user["name"]]);
    }
}