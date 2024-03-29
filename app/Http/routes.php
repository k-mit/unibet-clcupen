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
	return Redirect::to('https://apps.facebook.com/cl-cupen/');
});

/*
 * Campaign routes
 */
Route::any('facebook/canvas', ['uses' => 'FacebookController@viewCanvas', 'middleware' => 'auth.facebook']);
Route::any('facebook/login', ['uses' => 'FacebookController@viewLogin']);
Route::get('facebook/terms', function()
{
    return view('other.terms');
});
/*
 * Action routes for campaign page
 */
Route::post('/saveBet', ['middleware' => 'auth.facebook', 'uses' => 'FacebookController@saveBet']);
Route::post('/saveInvite', ['middleware' => 'auth.facebook', 'uses' => 'FacebookController@saveInvite']);

/**
 * admin routes
 */
Route::get('/admin/notifications', ['as' => 'notifications', 'middleware' => 'auth.facebook_admin', 'uses' => 'AdminController@notifications']);
Route::get('/admin/notificationsPersons', ['as' => 'notificationsPersons', 'middleware' => 'auth.facebook_admin', 'uses' => 'AdminController@notificationsPersons']);
Route::get('/admin/snippets', ['as' => 'snippets', 'middleware' => 'auth.facebook_admin', 'uses' => 'AdminController@snippets']);
Route::get('/admin', ['middleware' => 'auth.facebook_admin', 'uses' => 'AdminController@snippets']);
Route::get('/admin/roundResults/{round_id}', ['as' => 'roundResults', 'middleware' => 'auth.facebook_admin', 'uses' => 'AdminController@roundResults']);

Route::get('/logout', 'AdminController@logout');
/**
 * action routes for admin
 */
Route::get('/calculateRound', ['middleware' => 'auth.facebook_admin', 'uses' => 'FacebookController@calculateRound']);
Route::post('/admin/notifyAll', ['middleware' => 'auth.facebook_admin', 'uses' => 'AdminController@notifyAll']);
Route::post('/admin/notifyPersons', ['middleware' => 'auth.facebook_admin', 'uses' => 'AdminController@notifyPersons']);
Route::post('/admin/saveNotification', ['middleware' => 'auth.facebook_admin', 'uses' => 'AdminController@saveNotification']);
Route::post('/admin/saveSnippet', ['middleware' => 'auth.facebook_admin', 'uses' => 'AdminController@saveSnippet']);
Route::get('/admin/excelExport/{round_id}', ['as'=>'excel','middleware' => 'auth.facebook_admin', 'uses' => 'AdminController@excelExport']);
Route::controller('/admin/rounds','AdminRoundsController');
Route::controller('/admin/results','AdminResultsController');
Route::controller('/admin/matches','AdminMatchesController');
Route::controller('/admin/tiebreaker','AdminTiebreakerResultsController');
