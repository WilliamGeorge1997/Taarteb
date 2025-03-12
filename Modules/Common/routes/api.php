<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Common\App\Http\Controllers\Api\HistoryController;

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

Route::apiResource('history', HistoryController::class)->only(['index']);
