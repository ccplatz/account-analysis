<?php

use App\Http\Controllers\ChartDataApiController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/transactions/{transaction}/update', [TransactionController::class, 'update'])->name('api.transactions.update');

Route::post('/chart/transactions-by-category', [ChartDataApiController::class, 'transactionsByCategory'])->name('api.chart.transactions-by-category');