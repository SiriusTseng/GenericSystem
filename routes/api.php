<?php

use Illuminate\Http\Request;

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

Route::post('auth/login', 'Auth\AuthController@apiLogin');
Route::post('auth/register', 'Auth\AuthController@doRegister');
Route::group(['middleware' => 'auth:api'], function () {
    Route::prefix('auth')->group(function () {
        Route::post('userinfo', 'Auth\AuthController@userinfo');
    });


});
