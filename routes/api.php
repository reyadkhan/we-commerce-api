<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderTrackingHistoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SortController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('auth/user', function (Request $request) {
    return new UserResource($request->user());
});

/**
 * Login Controller
 */
Route::post('auth/login', LoginController::class);

/**
 * Logout Controller
 */
Route::post('auth/logout', LogoutController::class);

/**
 * Product Controller
 */
Route::get('products', [ProductController::class, 'index']);
Route::post('products', [ProductController::class, 'store']);
Route::get('products/{id}', [ProductController::class, 'show']);
Route::put('products/{id}', [ProductController::class, 'update']);
Route::delete('products/{id}', [ProductController::class, 'destroy']);

/**
 * Order Controller
 */
Route::get('orders', [OrderController::class, 'index']);
Route::post('orders', [OrderController::class, 'store']);
Route::get('orders/{id}', [OrderController::class, 'show']);
Route::put('orders/{id}', [OrderController::class, 'update']);
Route::delete('orders/{id}', [OrderController::class, 'destroy']);

/**
 * OrderTrackingHistory Controller
 */
Route::get('orders/{orderId}/tracking-histories', OrderTrackingHistoryController::class);

/**
 * Search Controller
 */
Route::post('search-products', [SearchController::class, 'searchProduct']);

/**
 * Sort Controller
 */
Route::get('sort-products', [SortController::class, 'sortProduct']);

/**
 * Register Controller
 */
Route::post('users', RegisterController::class);
