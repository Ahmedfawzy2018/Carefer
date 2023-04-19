<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\{AuthenticationController, BusController, ReservationController, RouteController};

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
    Route::post('login', [AuthenticationController::class, 'login']);
});

Route::group(['middleware' => 'auth'], function() {
    Route::get('most-frequent-trip', [ReservationController::class, 'mostFrequentTrip']);

    Route::apiResource('reservations', ReservationController::class);
    Route::apiResource('buses', BusController::class) ;
    Route::apiResource('routes', RouteController::class) ;

});


