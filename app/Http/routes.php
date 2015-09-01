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
Route::get('admin', 'SquareController@admin');
Route::post('admin/update', 'SquareController@adminUpdate');
Route::resource('purchase', 'SquareController');

Route::group(['prefix' => 'api', 'namespace' => 'Api'], function() {
	Route::resource('sets', 'SetController');
});
