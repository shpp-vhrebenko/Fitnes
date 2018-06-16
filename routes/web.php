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

/*Route::get('/_debugbar/assets/stylesheets', [
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
*/

Auth::routes();

Route::group([], function() {
	Route::get('/', 'HomeController@index')->name('index');
});

Route::group(['prefix'=>'my-account','middleware'=>'auth'], function() {
	Route::get('/', 'MyAccountController@index')->name('my-account');
});

Route::group(['prefix'=>'admin','middleware'=>'auth'], function() {
	Route::get('/', 'AdminController@index')->name('admin');

	// Route settings
	Route::get('/settings', 'AdminController@settings')->name('settings');
    Route::post('/settings', 'AdminController@settings_update')->name('settings_update');

    // Route clients
    Route::get('/clients', 'AdminController@clients')->name('clients');
    Route::get('/client/{id}', 'AdminController@client')->name('show_client');
    Route::get('/client/{id}/edit', 'AdminController@client_edit')->name('edit_client');
    Route::post('/client/{id}/edit', 'AdminController@client_update')->name('update_client');
    Route::delete('/client/{id}/destroy', 'AdminController@client_destroy')->name('destroy_client');
    Route::get('/clients/filter', 'AdminController@clients_filter')->name('clients_filter');
});