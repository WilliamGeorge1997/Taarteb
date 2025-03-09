<?php

use Illuminate\Support\Facades\Route;
use Modules\User\App\Http\Controllers\Api\UserController;
use Modules\User\App\Http\Controllers\Api\UserAuthController;

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

Route::group([
    'prefix' => 'user'
], function ($router) {
    Route::group(['prefix' => 'auth'], function ($router) {
        Route::post('login', [UserAuthController::class, 'login']);
        Route::post('logout', [UserAuthController::class, 'logout']);
        Route::post('refresh', [UserAuthController::class, 'refresh']);
        Route::post('me', [UserAuthController::class, 'me']);
    });
    Route::post('change-password', [UserController::class, 'changePassword']);
    Route::post('update-profile', [UserController::class, 'updateProfile']);
});
