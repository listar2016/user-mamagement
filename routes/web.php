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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('admin/home', 'HomeController@adminHome')->name('admin.home')->middleware('is_admin');

Route::resource('users', 'UserController');
Route::post('users/update', 'UserController@update')->name('users.update');
Route::get('users/destroy/{id}', 'UserController@destroy');

Route::resource('servers', 'ServerController');
Route::post('servers/update', 'ServerController@update')->name('servers.update');
Route::get('servers/destroy/{id}', 'ServerController@destroy');

Route::get('hostnames','ServerController@getUserHostNames')->name('hostnames');
