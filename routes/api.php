<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\API\UserController;

Route::get('/profile', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('tasks')->controller(TaskController::class)->group(function() {
    Route::post('/store', 'store')->name('tasks.create');
    Route::get('/index', 'index')->name('tasks.index');
    Route::get('/show/{id}', 'show')->name('tasks.show');
});

Route::prefix('users')->controller(UserController::class)->group(function() {
    Route::get('/index', 'index')->name('users.index');
    Route::get('/search', 'search')->name('users.search');
})->middleware('auth:sanctum');

Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('/register', 'register')->name('auth.register');
    Route::post('/login', 'login')->name('auth.login');
});
