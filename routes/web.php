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
    Route::get('/clients/new_client', 'AdminController@client_new')->name('new_client');
    Route::post('/clients/client_store', 'AdminController@client_store')->name('client_store');
    Route::post('/clients/client_message', 'AdminController@client_sendMessage')->name('send_message_client');

    // Route category
    Route::get('/categories', 'AdminController@categories')->name('categories');
    Route::get('/categories/new', 'AdminController@categories_new')->name('categories_new');
    Route::post('/categories/new', 'AdminController@categories_store')->name('categories_store');
    Route::get('/category/{id}', 'AdminController@show_category')->name('show_category');
    Route::get('/category/{id}/edit', 'AdminController@edit_category')->name('edit_category');
    Route::put('/category/{id}/edit', 'AdminController@update_category')->name('update_category');
    Route::delete('/category/{id}/destroy', 'AdminController@destroy_category')->name('destroy_category');

    // Route items
    Route::get('/items', 'AdminController@items')->name('admin_items');
    Route::get('/items/new', 'AdminController@items_new')->name('items_new');
    Route::post('/items/new', 'AdminController@items_store')->name('items_store');
//    Route::get('/item/{id}', 'AdminController@item')->name('admin_item');
    Route::get('/item/{id}/edit', 'AdminController@item_edit')->name('edit_item');
    Route::put('/item/{id}/edit', 'AdminController@item_update')->name('update_item');
    Route::delete('/item/{id}/destroy', 'AdminController@item_destroy')->name('destroy_item');
    Route::get('/items/filter', 'AdminController@items_filter')->name('items_filter');
});