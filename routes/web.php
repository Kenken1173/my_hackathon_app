<?php


use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\MilestonesWBSController;
use App\Http\Controllers\MilestonesFlowController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\MilestoneListController;
use App\Http\Controllers\UserGoalListController;

use App\Http\Controllers\LoginController;

use Illuminate\Support\Facades\Route;

// 認証必須のページ
Route::middleware('auth')->group(function () {
    Route::get('/', [UserGoalListController::class, 'index']); // ユーザーごとのゴール一覧画面
    Route::get('/list/{goal_id}', [MilestoneListController::class, 'get']); // ゴールごとのマイルストーン一覧
    Route::get('/milestones-wbs/{goal_id}', [MilestonesWBSController::class, 'get']);
    Route::get('/milestones-flow/{goal_id}', [MilestonesFlowController::class, 'get']);
});

// ログイン画面（既存デザインを使用）
Route::get('/login', [LoginController::class, 'get'])->middleware('guest')->name('login');

// 認証処理（Laravel Breeze）
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('guest');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// パスワードリセット（Breeze）
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

