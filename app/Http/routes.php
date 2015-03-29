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
	return '';
});

/*
 * Campaign routes
 */
Route::any('facebook/canvas', ['uses' => 'FacebookController@viewCanvas', 'middleware' => 'auth.facebook']);

/*
 * Action routes for campaign page
 */
Route::post('/saveBet', ['middleware' => 'auth.facebook', 'uses' => 'FacebookController@saveBet']);

/**
 * admin routes
 */
Route::get('/admin/notifications', ['as' => 'notifications', 'middleware' => 'auth.facebook', 'uses' => 'AdminController@notifications']);
Route::get('/admin/notificationsPersons', ['as' => 'notificationsPersons', 'middleware' => 'auth.facebook', 'uses' => 'AdminController@notificationsPersons']);
Route::get('/admin/snippets', ['as' => 'snippets', 'middleware' => 'auth.facebook', 'uses' => 'AdminController@snippets']);
Route::get('/admin', ['middleware' => 'auth.facebook', 'uses' => 'AdminController@snippets']);
Route::get('/admin', ['as' => 'roundResults', 'middleware' => 'auth.facebook', 'uses' => 'AdminController@roundResults']);
Route::get('/logout', 'AdminController@logout');
/**
 * action routes for admin
 */
Route::get('/calculateRound', ['middleware' => 'auth.facebook', 'uses' => 'FacebookController@calculateRound']);
Route::post('/admin/notifyAll', ['middleware' => 'auth.facebook', 'uses' => 'AdminController@notifyAll']);
Route::post('/admin/notifyPersons', ['middleware' => 'auth.facebook', 'uses' => 'AdminController@notifyPersons']);
Route::post('/admin/saveNotification', ['middleware' => 'auth.facebook', 'uses' => 'AdminController@saveNotification']);
Route::post('/admin/saveSnippet', ['middleware' => 'auth', 'auth.facebook' => 'AdminController@saveSnippet']);
