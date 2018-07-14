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
]);*/


Auth::routes();

Route::group([], function() {
	Route::get('/', 'HomeController@index')->name('index');
    Route::get('/register/{slug}', 'HomeController@register_user')->name('register_user');
    Route::post('/register/store/{slug}', 'HomeController@user_store')->name('store_user');
    Route::post('/register/users', 'HomeController@validate_email_user')->name('validate_email_user');  
    Route::get('/oplata', 'HomeController@oplata')->name('oplata');   
    Route::any('/oplata/result', 'HomeController@oplata_result')->name('oplata_result');
    Route::get('/oplata/success', 'HomeController@success_oplata')->name('success_oplata');
    Route::get('/oplata/error', 'HomeController@error_oplata')->name('error_oplata');

    Route::get('/test_message', 'HomeController@test_message')->name('test_message');
});

Route::group(['prefix'=>'my-account','middleware'=>['auth','isActive','isActiveCourse'] ], function() {

	Route::get('/', 'MyAccountController@index')->name('my-account');

    //Route notification
    Route::post('/notification', 'MyAccountController@check_user_notification')->name('check_user_notification');

    // Route trainings
    Route::get('/trainings', 'MyAccountController@show_trainings')->name('show_trainings');
    Route::get('/training/{id}', 'MyAccountController@show_training')->name('show_training');

    // Route categories
    Route::get('/category/{slug}', 'MyAccountController@show_category_items')->name('show_category_items');
    Route::get('/category/{category_slug}/{item_slug}', 'MyAccountController@show_item')->name('show_item');

    // Route results
    Route::get('/results', 'MyAccountController@show_results')->name('show_results');
    Route::post('/results/user', 'MyAccountController@get_results')->name('get_results');
    Route::get('/results/new', 'MyAccountController@add_result')->name('add_result');    
    Route::post('/results/new', 'MyAccountController@result_store')->name('result_store');
    Route::delete('/results/delete', 'MyAccountController@result_delete')->name('result_delete');

    // Route courses
    Route::get('/courses','MyAccountController@show_courses')->name('courses_list');   
    Route::post('/courses','MyAccountController@by_course')->name('by_course'); 

    // Route
    Route::get('/faq', 'MyAccountController@show_faq')->name('show_faq');
    
});

Route::group(['prefix'=>'admin','middleware' => 'isAdmin'], function() {
	Route::get('/', 'AdminController@index')->name('admin');

	// Route settings
	Route::get('/settings', 'AdminController@settings')->name('settings');
    Route::post('/settings', 'AdminController@settings_update')->name('settings_update');

    // Route orders
    Route::get('/orders', 'AdminController@orders')->name('orders');
    Route::get('/order/{id}', 'AdminController@order')->name('show_order');
    Route::get('/order/{id}/edit', 'AdminController@edit_order')->name('edit_order');
    Route::put('/order/{id}/edit', 'AdminController@update_order')->name('update_order');
    Route::delete('/order/{id}/delete', 'AdminController@destroy_order')->name('destroy_order');
    Route::get('/orders/filter', 'AdminController@orders_filter')->name('orders_filter');

    // Route clients
    Route::get('/clients', 'AdminController@clients')->name('clients');
    Route::get('/client/{id}', 'AdminController@client')->name('show_client');
    Route::get('/client/not_register/{id}', 'AdminController@client_not_register')->name('show_client_not_register');
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
    Route::get('/item/{id}/edit', 'AdminController@item_edit')->name('edit_item');
    Route::put('/item/{id}/edit', 'AdminController@item_update')->name('update_item');
    Route::delete('/item/{id}/destroy', 'AdminController@item_destroy')->name('destroy_item');
    Route::get('/items/filter', 'AdminController@items_filter')->name('items_filter');

    // Route results
    Route::get('results/{id}', 'AdminController@results')->name('admin_results');

    // Route courses
    Route::get('/courses', 'AdminController@show_courses')->name('show_courses');   
    Route::get('/courses/filter', 'AdminController@courses_filter')->name('courses_filter'); 
    Route::get('/course/{id}', 'AdminController@show_cours')->name('show_cours');    
    Route::get('/courses/new', 'AdminController@new_cours')->name('new_cours');
    Route::post('/course/new', 'AdminController@cours_store')->name('cours_store');
    Route::get('/course/{id}/edit', 'AdminController@cours_edit')->name('edit_cours');
    Route::put('/course/{id}/edit', 'AdminController@cours_update')->name('update_cours');
    Route::delete('/course/{id}/destroy', 'AdminController@cours_destroy')->name('destroy_cours');

    // Route course trainings
    Route::get('/courses/trainings/{id}', 'AdminController@course_trainings')->name('course_trainings');
    Route::get('/courses/training/{course_id}/{number_day}/new', 'AdminController@new_training')->name('new_training');
    Route::post('/courses/training/new', 'AdminController@training_store')->name('training_store');
    Route::get('/courses/training/{id}/edit', 'AdminController@training_edit')->name('edit_training');
    Route::put('/courses/training/{id}/edit', 'AdminController@training_update')->name('update_training');

    // Route courses
    Route::get('/marathons', 'AdminController@show_marathons')->name('show_marathons');
    Route::get('/marathons/filter', 'AdminController@marathons_filter')->name('marathons_filter');
    Route::get('/marathon/{id}', 'AdminController@show_marathon')->name('show_marathon');    
    Route::get('/marathons/new', 'AdminController@new_marathon')->name('new_marathon');
    Route::post('/marathon/new', 'AdminController@marathon_store')->name('marathon_store');
    Route::get('/marathon/{id}/edit', 'AdminController@edit_marathon')->name('edit_marathon');
    Route::put('/marathon/{id}/edit', 'AdminController@update_marathon')->name('update_marathon');
    Route::delete('/marathon/{id}/destroy', 'AdminController@marathon_destroy')->name('destroy_marathon');
});