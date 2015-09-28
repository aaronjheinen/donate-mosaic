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
Route::get('thanks/{purchase_id}', 'ViewController@thanks');
Route::resource('purchase', 'SquareController');
Route::post('available', 'SquareController@available');

Route::group(['prefix' => 'api', 'namespace' => 'Api'], function() {
	Route::resource('sets', 'SetController');
	Route::post('image/upload', 'MediaController@upload');
});

/* Admin Routes */
Route::get('/auth/login', 'Admin\ViewController@login');
Route::get('/auth/logout', 'Admin\ViewController@logout');

Route::controllers([
  'auth' => 'Auth\AuthController',
  'password' => 'Auth\PasswordController'
]);

/* Todo - separate views from api calls */
Route::group(['prefix' => 'admin', 'namespace' => 'Admin','middleware' => 'auth'], function() {
	Route::get('/', 'ViewController@index');
	Route::get('set', 'ViewController@set');
	Route::get('set/image', 'ViewController@image');
	Route::get('set/content', 'ViewController@content');
	Route::resource('purchases', 'PurchaseController');
	Route::resource('rewards', 'RewardController');
});

Route::group(['prefix' => 'api/admin', 'namespace' => 'Api\Admin','middleware' => 'auth'], function() {
	Route::resource('set', 'SetController');
	Route::resource('purchases', 'PurchaseController');
	Route::post('set/{id}/content', 'ContentController@update');
});
