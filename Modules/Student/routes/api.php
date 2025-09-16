<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Student\App\Http\Controllers\Api\StudentController;
use Modules\Student\App\Http\Controllers\Api\StudentRegisterController;

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

Route::apiResource('students', StudentController::class)->only(['index', 'store', 'update', 'destroy']);
Route::group(['prefix' => 'students'], function () {
    Route::get('graduate', [StudentController::class, 'getStudentsToGraduate']);
    Route::post('graduate', [StudentController::class, 'graduate']);
    Route::post('upgrade', [StudentController::class, 'upgrade']);
    Route::post('register', [StudentRegisterController::class, 'register']);
});
Route::get('classes/{class}/students/upgrade', [StudentController::class, 'getStudentsToUpgrade']);
Route::post('students/import', [StudentController::class, 'importStudents']);
