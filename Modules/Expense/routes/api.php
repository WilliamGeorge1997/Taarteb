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
    Route::get('expenses/{expense}/exceptions', [ExpenseController::class, 'exceptions'])->name('expenses.exceptions.index');
    Route::post('expenses/{expense}/exceptions', [ExpenseController::class, 'storeExceptions'])->name('expenses.exceptions.store');
    Route::post('expenses/{expense}/exceptions/update', [ExpenseController::class, 'updateExceptions'])->name('expenses.exceptions.update');
});
