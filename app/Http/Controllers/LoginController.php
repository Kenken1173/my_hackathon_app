<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// ゴールを選択し、マイルストーンの一覧を表示する画面
class LoginController extends Controller
{
    public function get()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view("login");
    }
}