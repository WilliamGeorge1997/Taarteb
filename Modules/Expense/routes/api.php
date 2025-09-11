<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Expense\App\Http\Controllers\Api\ExpenseController;

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

Route::group(['prefix' => 'admin'], function () {
    Route::apiResource('expenses', ExpenseController::class)->only(['index', 'store']);
});
