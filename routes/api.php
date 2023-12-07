<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PrinterController;
use App\Http\Controllers\ProductController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/orders', [OrderController::class, 'index'])->name('orders');
Route::post('/products', [ProductController::class, 'index'])->name('products');

Route::name('printers')
->prefix('/printers')
->middleware('Auth')
->group(function() {
    Route::get('/', [PrinterController::class, 'index'])->name('index');
    Route::get('/{type}', [PrinterController::class, 'show'])->name('show');
    Route::put('/{type}', [PrinterController::class, 'update'])->name('update');
});

