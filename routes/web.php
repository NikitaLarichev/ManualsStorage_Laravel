<?php

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

//Route::get('/', function () {
//    return view('manuals_list');
//});
Route::get('/', [App\Http\Controllers\ManualController::class, 'index']);
Auth::routes();
Route::get('/search', [App\Http\Controllers\ManualController::class, 'search'])->name('search');;
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/download/{filename}', [App\Http\Controllers\ManualController::class, 'downloadManual']);
Route::get('/read/{filename}', [App\Http\Controllers\ManualController::class, 'readManual']);
Route::post('/confirm', [App\Http\Controllers\UnconfirmedManualController::class, 'confirmManual'])->name('confirm');
Route::post('/delete_uc', [App\Http\Controllers\UnconfirmedManualController::class, 'deleteManual'])->name('delete_uc');
Route::get('/manual_loading', [App\Http\Controllers\UnconfirmedManualController::class, 'manualLoading']);
Route::post('/delete', [App\Http\Controllers\ManualController::class, 'deleteManual'])->name('delete');
Route::get('/unconfirmed_manuals_list', [App\Http\Controllers\UnconfirmedManualController::class, 'getUnconfirmedManualsList']);
Route::get('/user_list', [App\Http\Controllers\UserController::class, 'index']);
Route::post('/loading', [App\Http\Controllers\UnconfirmedManualController::class, 'update'])->name('loading');
Route::post('/blocking', [App\Http\Controllers\UserController::class, 'toBlock'])->name('blocking');
