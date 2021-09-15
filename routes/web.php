<?php

// use Illuminate\Support\Facades\Route;

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

//  login
Route::get('/', 'LoginController@index');
Route::post('/login', 'LoginController@login');
Route::post('/logout', 'LoginController@logout');

// home
Route::get('/home', 'HomeController@index');

// profile
Route::get('/gantipassword/{id}', 'GantiPasswordController@index');
Route::post('/gantipassword/update', 'GantiPasswordController@update');

// master data - role access
Route::get('masterdata/roleaccess', 'masterData\RoleAccessController@index');
Route::get('masterdata/roleaccess/create', 'MasterData\RoleAccessController@create');
Route::get('masterdata/roleaccess/detail/{id}', 'MasterData\RoleAccessController@detail');
Route::get('masterdata/roleaccess/edit/{id}', 'MasterData\RoleAccessController@edit');
Route::post('masterdata/roleaccess/store', 'MasterData\RoleAccessController@store');
Route::post('masterdata/roleaccess/update', 'MasterData\RoleAccessController@update');
Route::post('masterdata/roleaccess/delete', 'MasterData\RoleAccessController@delete');
Route::post('masterdata/roleaccess/active', 'MasterData\RoleAccessController@active');

//master data - users
Route::get('masterdata/users', 'MasterData\UsersController@index');
Route::get('masterdata/users/create', 'MasterData\UsersController@create');
Route::get('masterdata/users/detail/{id}', 'MasterData\UsersController@detail');
Route::get('masterdata/users/edit/{id}', 'MasterData\UsersController@edit');
Route::post('masterdata/users/store', 'MasterData\UsersController@store');
Route::post('masterdata/users/update', 'MasterData\UsersController@update');
Route::post('masterdata/users/delete', 'MasterData\UsersController@delete');
Route::post('masterdata/users/active', 'MasterData\UsersController@active');




Route::get('clear-cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('config:cache');
    return "Cache is cleared";
});
