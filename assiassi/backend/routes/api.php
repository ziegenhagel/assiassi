<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TeamController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::apiResource('teams', TeamController::class);
Route::delete('teams','App\Http\Controllers\TeamController@destroy');
Route::put('team/{team}','App\Http\Controllers\TeamController@update');
Route::post('team/{team}/shooting','App\Http\Controllers\ShootingController@store');
