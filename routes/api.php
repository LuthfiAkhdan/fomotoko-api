<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

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

// Product routes
Route::get('/product-list', [ProductController::class, 'index']);
Route::post('/product-store', [ProductController::class, 'store']);
Route::get('/product/{id}', [ProductController::class, 'find']);
Route::post('/product/{id}', [ProductController::class, 'update']);
Route::delete('/product/{id}', [ProductController::class, 'destroy']);

// Order routes
Route::post('/order', [OrderController::class, 'store']);
Route::get('/order/{id}', [OrderController::class, 'find']);
// delete selected order items
Route::delete('/order/{id}/item/{item_id}', [OrderController::class, 'destroy_item']);
// delete order and delete all order items
Route::delete('/order/{id}', [OrderController::class, 'destroy']);
