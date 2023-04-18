<?php

use App\Http\Controllers\Api\V1\MovieController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthenticationController;

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

Route::group(['prefix' => 'user'], function() {
    Route::post('register', [AuthenticationController::class, 'store']);
    Route::post('login', [AuthenticationController::class, 'login']);
    Route::group(['middleware' => 'auth'], function() {

    });
});


