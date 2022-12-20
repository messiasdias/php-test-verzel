<?php

use Illuminate\Support\Facades\Route;

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
    'prefix' => 'dashboard',
    'namespace' => 'App\Http\Controllers',
], function () {
    Route::get('/', 'DashboardController@index');
});

Route::group([
    'prefix' => 'auth',
    'namespace' => 'App\Http\Controllers',
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

Route::group([
    'prefix' => 'users',
    'namespace' => 'App\Http\Controllers',
], function () {
    Route::post('/', 'UserController@store');
    Route::delete('/', 'UserController@delete');
    Route::get('/{id}', 'UserController@getOne')->where('id', '[0-9]+');
    Route::get('/', 'UserController@getAll');
    Route::get('/send-confirm-mail/{id}', 'UserController@sendConfirmMail')->where('id', '[0-9]+');
    Route::get('/confirm-mail/{id}/{hash}', 'UserController@confirmMail')->where('id', '[0-9]+');
    Route::get('/permissions', 'UserController@getPermissionsList');
    Route::post('/permissions', 'UserController@setPermissions');
});

Route::group([
    'prefix' => 'vehicles',
    'namespace' => 'App\Http\Controllers',
], function () {
    Route::post('/', 'VehicleController@store');
    Route::delete('/', 'VehicleController@delete');
    Route::get('/{id}', 'VehicleController@getOne')->where('id', '[0-9]+');
    Route::get('/', 'VehicleController@getAll');
    Route::get('/search', 'VehicleController@search');
});
