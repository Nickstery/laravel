<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::any('test', function(){
    die('test');
});

Route::post('auth/register', 'AuthController@register');
Route::post('auth/login', 'AuthController@login');
Route::any('logout', 'AuthController@logout');

Route::group(['prefix' => 'v1', 'middleware' => ['auth']],function(){
    Route::resource('user', 'UsersController');
    Route::resource('cards', 'CardsController');
});
