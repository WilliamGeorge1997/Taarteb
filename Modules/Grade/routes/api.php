<?php

use Illuminate\Support\Facades\Route;
use Modules\Grade\App\Http\Controllers\Api\GradeController;

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

Route::get('grades', [GradeController::class, 'index']);
