<?php

use App\Http\Controllers\AppSPAController;
use App\Http\Controllers\AuthSPAController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::group(['middleware' => ['guest']], function () {
    
// });
Route::get('/login', [AppSPAController::class, 'index'])->name('login');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/{any}', [AppSPAController::class, 'index'])->where('any', '.*');
});

