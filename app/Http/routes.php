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
Route::resource('purchase', 'SquareController');

/* Todo - separate views from api calls */
Route::group(['prefix' => 'admin'], function() {
	Route::get('/', 'SquareController@admin');
	Route::post('update', 'SquareController@adminUpdate');
	Route::get('puchases', 'ViewController@purchases');
});
Route::group(['prefix' => 'api', 'namespace' => 'Api'], function() {
	Route::resource('sets', 'SetController');
	Route::post('image/upload', 'MediaController@upload');
});
Route::group(['prefix' => 'api/admin', 'namespace' => 'Api\Admin'], function() {
	Route::resource('sets', 'SetController');
	Route::resource('purchases', 'PurchaseController');
});
