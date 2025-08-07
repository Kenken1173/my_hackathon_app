<?php


use App\Http\Controllers\UserController;
use App\Http\Controllers\GoalController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/users', [UserController::class, "index"]);
Route::get('/users/{id}', [UserController::class, "get"]);

Route::get('/goals', [GoalController::class, "index"]);
Route::get('/goals/{id}', [GoalController::class, 'get']);
