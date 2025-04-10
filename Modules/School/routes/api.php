<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\School\App\Http\Controllers\Api\SchoolController;

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

Route::apiResource('schools',SchoolController::class);
Route::post('schools/import', [SchoolController::class, 'importSchools']);
