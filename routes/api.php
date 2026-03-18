<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StatusController;
use App\Models\Category;
use App\Models\Item;
use App\Models\Order;
use App\Models\Status;

Route::prefix('account')->controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::get('me', 'me')->middleware('auth:sanctum');
    Route::post('logout', 'logout')->middleware('auth:sanctum');
    Route::get('details', 'details')->middleware('auth:sanctum');
    Route::post('is-token-still-valid', 'isTokenStillValid');
});
Route::middleware('auth:sanctum')->prefix('items')->controller(ItemController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/{item}', 'show');
    Route::post('/', 'create')->can('create', Item::class);
    Route::patch('/{item}', 'update')->can('update', 'item');
    Route::delete('/{item}', 'destroy')->can('delete', 'item');
    Route::post('/{item}/toggle-active', 'toggleItemActiveStatus')->can('update', 'item');
    Route::post('/{item}/toggle-featured', 'toggleItemFeaturedStatus')->can('update', 'item');
});

Route::middleware('auth:sanctum')->prefix('orders')->controller(OrderController::class)->group(function () {
    Route::get('/', 'index');
    // Route::post('/', 'store')->can('create', Order::class);
    Route::get('/breaks/{date?}', 'getBreaks');
    Route::patch('/{order}', 'update')->can('update', 'order');
    Route::get('/{order}', 'show')->can('view', 'order');
});

Route::middleware('auth:sanctum')->prefix('categories')->controller(CategoryController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store')->can('create', Category::class);
    Route::get('/{category}', 'show')->can('view', 'category');
    Route::patch('/{category}', 'update')->can('update', 'category');
    Route::delete('/{category}', 'destroy')->can('delete', 'category');
});

Route::prefix('payment')->controller((PaymentController::class))->group(function () {
    Route::post('/checkout', 'checkout')->middleware('auth:sanctum');
    Route::post('/webhook', 'handle');
});

Route::middleware('auth:sanctum')->prefix('statuses')->controller(StatusController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store')->can('create', Status::class);
    Route::get('/{status}', 'show')->can('view', 'status');
    Route::delete('/{status}', 'destroy')->can('delete', 'status');
});
