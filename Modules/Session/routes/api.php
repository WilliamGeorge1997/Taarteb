<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Session\App\Http\Controllers\Api\SessionController;

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

Route::apiResource('session', SessionController::class);
