<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Admin\App\Http\Controllers\Api\AdminAuthController;

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

// Route::middleware(['auth:sanctum'])->prefix('v1')->name('api.')->group(function () {
//     Route::get('admin', fn (Request $request) => $request->user())->name('admin');
// });



Route::group([
    'prefix' => 'admin/auth'
],function($router){
    Route::post('login',[AdminAuthController::class,'login']);
    Route::post('logout',[AdminAuthController::class,'logout']);
    Route::post('refresh',[AdminAuthController::class,'refresh']);
    Route::post('me',[AdminAuthController::class,'me']);
});