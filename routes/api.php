<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/team','TeamsController@store');
Route::get('/team/{id}','TeamsController@show');
Route::post('/team/{team_id}/member','TeamMembersController@store');
Route::post('/team/{team_id}/tasks','TasksController@store');
Route::get('/team/{team_id}/tasks','TasksController@index');
Route::get('/team/{team_id}/members/{member_id}/tasks/','TasksController@member_index');
Route::get('/team/{team_id}/tasks/{id}','TasksController@show');
Route::patch('/team/{team_id}/tasks/{id}','TasksController@update');
Route::delete('/team/{team_id}/member/{id}','TeamMembersController@destroy');
