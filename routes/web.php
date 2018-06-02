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

// TODO Refactor: move auth to Controllers?
// https://laravel.com/docs/5.6/authentication#protecting-routes
// https://laravel.com/docs/5.6/controllers#controller-middleware

// TODO Add auth here, too, please.
Route::get('/', function () {
    return view('welcome');
});

// Projects
Route::get('/projects', 'ProjectsController@index')->middleware('auth');
Route::get('/projects/{project}', 'ProjectsController@show')->middleware('auth');
// TODO This one needs the auth middleware, no?
// TODO What does this one do?
Route::post('/projects/{project}/tasks', 'TasksController@store');

// Tasks
Route::get('/tasks', 'TasksController@index');
Route::get('/tasks/{task}', 'TasksController@show');
// TODO This one needs the auth middleware, no?
// TODO What does this one do?
Route::post('/tasks/{task}/projects', 'TasksController@store');

// Framework generated helper for Authentication.
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
