<?php


use App\Http\Controllers\MileStoneTableController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MilestoneListController;
use App\Http\Controllers\UserListGoalViewController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserListGoalViewController::class, "index"]); // ユーザーごとのゴール一覧画面
Route::get('/list/{goal_id}', [MilestoneListController::class, "get"]); // ユーザーのゴールごとのマイルストーン一覧画面
Route::get('/table/{goal_id}', [MileStoneTableController::class, "get"]); // ユーザーのゴールごとのマイルストーン一覧画面
