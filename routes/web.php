<?php

//dd(App::environment());


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

// See the Home Controller for an example of protecting the entire Controller
// with auth.
Route::get('/home/', 'HomeController@index')->name('home');
// where() constrains the route to the regex supplied.
Route::get('/{eventdate?}', 'HomeController@index')->name('home')->where('eventdate', '\d\d\d\d-\d\d-\d\d');
Route::get('/', 'HomeController@index')->name('home');
#Route::get('/', 'HomeController@index')->name('home');
#Route::get('/home/', 'HomeController@index')->name('home');

// TODO Add auth here, too, please.
//Route::get('/', function () {
//    return view('welcome');
//});

// Events
Route::get('/events/{eventdate?}', 'EventController@index');
Route::get('/events/create', 'EventController@create');
//Route::post('/events', 'EventController@store')->middleware(\App\Http\Middleware\processTemporalInputs::class);
Route::post('/events', 'EventController@store');


// Projects
Route::get('/projects', 'ProjectsController@index')->name('projects-list')->middleware('auth');
Route::get('/projects/{project}', 'ProjectsController@show')->middleware('auth');
// TODO This one needs the auth middleware, no?
// TODO What does this one do?
Route::post('/projects/{project}/tasks', 'TasksController@store');

// Tasks
Route::get('/tasks', 'TasksController@index')->name('tasks-list');
Route::get('/tasks/{task}', 'TasksController@show');
// TODO This one needs the auth middleware, no?
// TODO What does this one do?
Route::post('/tasks/{task}/projects', 'TasksController@store');

// Framework generated helper for Authentication.
Auth::routes();

