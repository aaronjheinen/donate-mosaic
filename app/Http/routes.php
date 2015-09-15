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

Route::get('/', 'SquareController@index');
Route::get('/thank-you', 'SquareController@purchased');
Route::resource('purchase', 'SquareController');

Route::group(['prefix' => 'api', 'namespace' => 'Api'], function() {
	Route::resource('sets', 'SetController');
	Route::post('image/upload', 'MediaController@upload');
});

/* Admin Routes */
Route::get('/auth/login', 'Admin\ViewController@index');
Route::get('/auth/logout', 'Admin\ViewController@logout');

Route::controllers([
  'auth' => 'Auth\AuthController',
  'password' => 'Auth\PasswordController'
]);

/* Todo - separate views from api calls */
Route::group(['prefix' => 'admin','middleware' => 'auth'], function() {
	Route::get('/', 'SquareController@admin');
	Route::post('update', 'SquareController@adminUpdate');
	Route::get('purchases', 'Admin\ViewController@purchases');
	Route::resource('rewards', 'Admin\RewardController');
});

Route::group(['prefix' => 'api/admin', 'namespace' => 'Api\Admin','middleware' => 'auth'], function() {
	Route::resource('sets', 'SetController');
	Route::resource('purchases', 'PurchaseController');
});
