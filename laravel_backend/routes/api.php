<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RolesController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\ExpenseCategoriesController;
use App\Http\Controllers\Api\ExpensesController;
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

Route::post('logout', [AuthController::class, 'logout']);

// TODO: make 'middleware' => 'auth:api' work. Currently the Authorization 
// is not set to ey... but instead the token directly which might be
// causing passport to not pick up the token
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::post('me/password', [UsersController::class, 'updatePassword']);

    Route::get('expense-categories', [ExpenseCategoriesController::class, 'index']);
    Route::get('expenses/by-category', [ExpensesController::class, 'getCategoryExpenses']);
    Route::apiResource('expenses', ExpensesController::class);

    Route::group(['middleware' => 'admin'], function () {
        Route::apiResource('users', UsersController::class);
        Route::apiResource('roles', RolesController::class);
        Route::apiResource('expense-categories', ExpenseCategoriesController::class)->except(['index']);
    });
});
