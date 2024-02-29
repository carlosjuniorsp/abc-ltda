<?php

use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SalesController;
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



/**
 * Products Router
 */
Route::post('/products', [ProductsController::class, 'store'])->name('store');
Route::get('/products', [ProductsController::class, 'index'])->name('index');
/**
 * Sales's Router
 */
Route::post('/sales', [SalesController::class, 'store'])->name('store');
Route::get('/sales', [SalesController::class, 'index'])->name('index');
Route::get('/sales/{id}', [SalesController::class, 'showSale'])->name('showSale');
