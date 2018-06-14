<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/_debugbar/assets/stylesheets', [
    'as' => 'debugbar-css',
    'uses' => '\Barryvdh\Debugbar\Controllers\AssetController@css'
]);

Route::get('/_debugbar/assets/javascript', [
    'as' => 'debugbar-js',
    'uses' => '\Barryvdh\Debugbar\Controllers\AssetController@js'
]);

Route::get('/_debugbar/open', [
    'as' => 'debugbar-open',
    'uses' => '\Barryvdh\Debugbar\Controllers\OpenController@handler'
]);


Auth::routes();

Route::group([], function() {
	Route::get('/', 'TestController@index')->name('index');
});

Route::group(['prefix'=>'my-account','middleware'=>'auth'], function() {
	Route::get('/', 'HomeController@index')->name('home');
});

Route::group(['prefix'=>'admin','middleware'=>'auth'], function() {
	Route::get('/', 'HomeController@index')->name('home');
});