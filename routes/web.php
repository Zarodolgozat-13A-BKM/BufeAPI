<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;

Route::prefix('account')->controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout')->middleware('auth:sanctum');
    Route::post('change-password', 'changePassword')->middleware('auth:sanctum');
    Route::post('forgot-password', 'forgotPassword');
});
Route::prefix('items')->controller(ItemController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
    Route::post('/', 'create');
    Route::put('/{id}', 'update');
    Route::delete('/{id}', 'delete');
    Route::post('/{id}/toggle-active', 'toggleItemActiveStatus');
})->middleware('auth:sanctum');
