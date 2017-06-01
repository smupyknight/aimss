<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
	return redirect('/login');
});

// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login');
$this->post('logout', 'Auth\LoginController@logout');

// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
$this->post('password/reset', 'Auth\ResetPasswordController@reset');

Route::group(['middleware' => ['auth']], function () {
	Route::get('/dashboard', 'HomeController@index');

	// Users
	Route::get('/users', 'UsersController@getIndex');
	Route::get('/users/list/{status}', 'UsersController@getList');
	Route::get('/users/create', 'UsersController@getCreate');
	Route::post('/users/create', 'UsersController@postCreate');
	Route::get('/users/view/{id}', 'UsersController@getView');
	Route::get('/users/export-subscribers', 'UsersController@exportSubscribers');
	Route::get('/users/edit/{id}', 'UsersController@getEdit');
	Route::post('/users/edit/{id}', 'UsersController@postEdit');
	Route::get('/users/reinvite/{id}', 'UsersController@getReinvite');
	Route::post('/users/reinvite/{id}', 'UsersController@postReinvite');
	Route::get('/users/accept/{id}', 'UsersController@getAccept');
	Route::post('/users/accept/{id}', 'UsersController@postAccept');
	Route::get('/users/enable/{id}', 'UsersController@getEnable');
	Route::post('/users/enable/{id}', 'UsersController@postEnable');
	Route::get('/users/disable/{id}', 'UsersController@getDisable');
	Route::post('/users/disable/{id}', 'UsersController@postDisable');
	Route::get('/users/delete/{id}', 'UsersController@getDelete');
	Route::post('/users/delete/{id}', 'UsersController@postDelete');

	// User images
	Route::get('/user-images/view/{filename}', 'UserImagesController@getView');

	// Invitations
	Route::get('/users/invite', 'UsersController@getInvite');
	Route::post('/users/invite', 'UsersController@postInvite');

	// Events
	Route::get('/events', 'EventsController@getIndex');
	Route::get('/events/list/{type}', 'EventsController@getList');
	Route::get('/events/pending', 'EventsController@getPending');

	Route::get('/events/create', 'EventsController@getCreate');
	Route::post('/events/create', 'EventsController@postCreate');

	Route::get('/events/view/{id}', 'EventsController@getView');
	Route::get('/events/edit/{id}', 'EventsController@getEdit');
	Route::post('/events/edit/{id}', 'EventsController@postEdit');
	Route::get('/events/delete/{id}', 'EventsController@getDelete');

	Route::post('/events/add-stage', 'EventsController@postAddStage');
	Route::post('/events/edit-stage/{id}', 'EventsController@postEditStage');
	Route::post('/events/delete-stage/{id}', 'EventsController@postDeleteStage');

	// Incidents
	Route::get('/incidents/view/{id}', 'IncidentsController@getView');

	// Form submissions
	Route::get('/submissions/view/{id}', 'SubmissionsController@getView');

	// Review
	Route::get('/review', 'ReviewController@getIndex');
	Route::get('/review/start/{id}', 'ReviewController@getStart');
	Route::post('/review/start/{id}', 'ReviewController@postStart');
	Route::get('/review/do/{id}', 'ReviewController@getDo');
	Route::post('/review/do/{id}', 'ReviewController@postDo');

	// Form Builder
	Route::get('/formbuilder', 'FormBuilderController@getIndex');
	Route::post('/formbuilder/create-category', 'FormBuilderController@postCreateCategory');
	Route::post('/formbuilder/create-question', 'FormBuilderController@postCreateQuestion');
	Route::post('/formbuilder/edit-category/{id}', 'FormBuilderController@postEditCategory');
	Route::post('/formbuilder/edit-question/{id}', 'FormBuilderController@postEditQuestion');
	Route::post('/formbuilder/delete-category/{id}', 'FormBuilderController@postDeleteCategory');
	Route::post('/formbuilder/delete-question/{id}', 'FormBuilderController@postDeleteQuestion');
	Route::get('/formbuilder/questions/{category_id}', 'FormBuilderController@getQuestions');
});

Route::get('/invitations/accept/{token}', 'InvitationsController@getAccept');
Route::post('/invitations/accept/{token}', 'InvitationsController@postAccept');

