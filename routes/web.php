<?php


use App\Http\Controllers\MileStoneTableController;
use App\Http\Controllers\MilestonesWBSController;
use App\Http\Controllers\MilestonesFlowController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\MilestoneListController;
use App\Http\Controllers\UserGoalListController;

use App\Http\Controllers\LoginController;

use Illuminate\Support\Facades\Route;

Route::get('/', [UserGoalListController::class, "index"]); // ユーザーごとのゴール一覧画面
Route::get('/list/{goal_id}', [MilestoneListController::class, "get"]); // ユーザーのゴールごとのマイルストーン一覧画面
Route::get('/table/{goal_id}', [MileStoneTableController::class, "get"]); // ユーザーのゴールごとのマイルストーン一覧画面
Route::get('/milestones-wbs/{goal_id}', [MilestonesWBSController::class, "get"]);

Route::get('/login', [LoginController::class, "get"]);
Route::get('/milestones-flow/{goal_id}', [MilestonesFlowController::class, "get"]);


