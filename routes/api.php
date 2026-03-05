<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CategoryController;
use App\Models\Category;
use App\Models\Item;
use App\Models\Order;

Route::prefix('account')->controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::get('me', 'me')->middleware('auth:sanctum');
    Route::post('logout', 'logout')->middleware('auth:sanctum');
});
Route::middleware('auth:sanctum')->prefix('items')->controller(ItemController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
    Route::post('/', 'create')->can('create', Item::class);
    Route::put('/{id}', 'update')->can('update', Item::class);
    Route::delete('/{id}', 'delete')->can('delete', Item::class);
    Route::post('/{id}/toggle-active', 'toggleItemActiveStatus')->can('update', Item::class);
});

Route::middleware('auth:sanctum')->prefix('orders')->controller(OrderController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store')->can('create', Order::class);
    Route::get('/{id}', 'show')->can('view', Order::class);
});

Route::middleware('auth:sanctum')->prefix('categories')->controller(CategoryController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store')->can('create', Category::class);
    Route::get('/{id}', 'show');
    Route::put('/{id}', 'update')->can('update', Category::class);
    Route::delete('/{id}', 'delete')->can('delete', Category::class);
});
