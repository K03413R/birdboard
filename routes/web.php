<?php

use App\Project;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => 'auth'], function(){
    //home
    Route::get('/', 'ProjectsController@index');

    //contains all seven crud actions
    Route::resource('projects', 'ProjectsController');

    Route::post('projects/{project}/tasks', 'ProjectTasksController@store');
    Route::patch('projects/{project}/tasks/{task}', 'ProjectTasksController@update');

    Route::post('projects/{project}/invitations', 'ProjectInvitationsController@store');
});

Auth::routes();

