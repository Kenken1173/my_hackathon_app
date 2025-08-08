<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// ゴールを選択し、マイルストーンの一覧を表示する画面
class LoginController extends Controller
{
    public function get()
    {
        return view("login");
    }
}