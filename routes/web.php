<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FilesController;
use App\Http\Controllers\ImportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/**
 * Home
 */
Route::get('/', function () {
    return view('home.home');
})->name('home');

/**
 * File Upload outes
 */
// TODO: Rename routes, use same routes as for source controller
Route::get('/files', [FilesController::class, 'index'])->name('files.index');
Route::post('/files/add', [FilesController::class, 'store'])->name('files.store');
Route::get('/files/{file}/delete', [FilesController::class, 'destroy'])->name('files.delete');

/**
 * Account routes
 */
Route::resource('/accounts', AccountController::class); // TODO: Set except routes

/**
 * Import csv routes
 */
Route::post('/import/choose-account', [ImportController::class, 'chooseAccount'])->name('import.choose-account');
Route::post('/import/map-fields', [ImportController::class, 'mapFields'])->name('import.map-fields');
Route::post('/import/store-transactions', [ImportController::class, 'storeTransactions'])->name('import.store-transactions');

/**
 * Categories routes
 */
Route::resource('/categories', CategoryController::class); // TODO: Set except routes