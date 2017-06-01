<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['namespace' => 'Api\V1', 'prefix' => 'v1'], function() {
	Route::post('auth/login', 'AuthController@postLogin');
	Route::post('auth/forgot-password', 'AuthController@postForgotPassword');
	Route::post('auth/reset-password', 'AuthController@postResetPassword');
	Route::post('auth/signup', 'AuthController@postSignup');

	Route::group(['middleware' => ['auth:api']], function() {
		Route::get('account', 'AccountController@getIndex');
		Route::post('account/edit', 'AccountController@postEdit');

		Route::get('events/list/{filter}', 'EventsController@getList');
		Route::get('events/{event_id}', 'EventsController@getView');
		Route::get('events', 'EventsController@getIndex');
		Route::post('events/create', 'EventsController@postCreate');

		Route::post('incidents/create', 'IncidentsController@postCreate');
		Route::get('incidents/list/{id}', 'IncidentsController@getList');
		Route::get('incidents/form', 'IncidentsController@getForm');
		Route::post('incidents/form', 'IncidentsController@postForm');

		Route::get('injuries/list/{id}', 'InjuriesController@getList');
		Route::post('injuries/create', 'InjuriesController@postCreate');
		Route::post('injuries/edit/{id}', 'InjuriesController@postEdit');
		Route::post('injuries/delete/{id}', 'InjuriesController@postDelete');
		Route::get('injuries/view/{id}', 'InjuriesController@getView');

		Route::get('submissions', 'SubmissionsController@getIndex');
		Route::get('submissions/view/{id}', 'SubmissionsController@getView');

		Route::get('user-images', 'UserImagesController@getIndex');
		Route::post('user-images/create', 'UserImagesController@postCreate');
		Route::post('user-images/delete', 'UserImagesController@postDelete');

		Route::get('damage/list/{id}', 'DamageController@getList');
		Route::post('damage/create/{id}', 'DamageController@postCreate');

		Route::post('media/create/{id}', 'MediaController@postCreate');
		Route::get('media/{id}', 'MediaController@getIndex');
	});
});
