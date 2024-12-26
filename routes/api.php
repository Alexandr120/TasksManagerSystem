<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(\App\Http\Controllers\Api\AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
});

Route::resource('tasks', \App\Http\Controllers\Api\TaskController::class);

Route::controller(\App\Http\Controllers\Api\CommentController::class)
    ->prefix('/tasks/{task}/comments')
    ->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
});
Route::delete('comments/{comment}', [\App\Http\Controllers\Api\CommentController::class, 'destroy']);

Route::resource('teams', \App\Http\Controllers\Api\TeamController::class);
Route::controller(\App\Http\Controllers\Api\TeamController::class)
    ->prefix('/teams/{team}/users')
    ->group(function () {
        Route::post('/', 'syncUsers');
        Route::delete('/{userId}', 'removeUser');
    });
