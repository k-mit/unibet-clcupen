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
use \App\Http\Controllers\FacebookController;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

Route::get('/', function () {return '';});
Route::any('facebook/canvas', [
    'uses' => 'facebookController@viewCanvas',
    'middleware' => 'auth.facebook',
]);

Route::get('/calculateRound', ['middleware' => 'auth.facebook', 'uses' => 'FacebookController@calculateRound']);
Route::post('/saveBet', ['middleware' => 'auth.facebook', 'uses' => 'FacebookController@saveBet']);
Route::post('/admin/notifyAll', ['middleware' => 'auth.facebook', 'uses' => 'AdminController@notifyAll']);
Route::post('/admin/notifyPersons', ['middleware' => 'auth.facebook', 'uses' => 'AdminController@notifyPersons']);
Route::get('/admin/notifications', ['middleware' => 'auth.facebook', 'uses' => 'AdminController@notifications']);
Route::get('/admin/notificationsPersons', ['middleware' => 'auth.facebook', 'uses' => 'AdminController@notificationsPersons']);
Route::get('/admin/snippets', ['middleware' => 'auth', 'auth.facebook' => 'AdminController@snippets']);
Route::post('/admin/saveNotification', ['middleware' => 'auth.facebook', 'uses' => 'AdminController@saveNotification']);
Route::post('/admin/saveSnippet', ['middleware' => 'auth', 'auth.facebook' => 'AdminController@saveSnippet']);

