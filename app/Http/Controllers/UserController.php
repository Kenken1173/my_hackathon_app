<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public static function loadJson() {
        return json_decode(file_get_contents(storage_path("dataStore.json")), true)["users"];
    }
    private static function updateJson($json) {
        # TODO 今後やるかも？
    }
    // フォーム表示
    public function index()
    {
        $users = UserController::loadJson();
        return $users;
    }
    public function get($id)
    {
        $users = UserController::loadJson();
        foreach ($users as $user) {
            if ($user["id"] == $id) {
                return $user;
            }
        }
        return null;
    }
}