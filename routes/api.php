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

Route::get('/posts', 'PostsController@index');
Route::post('/posts/store', 'PostsController@store');
Route::get('/posts/{id?}', 'PostsController@show');
Route::post('/posts/update/{id?}', 'PostsController@update');
Route::delete('/posts/{id?}', 'PostsController@destroy');


Route::post('location/kota', 'Api\GeneralController@locationkota');
Route::post('subtipe', 'Api\GeneralController@subtipe');
Route::post('asset', 'Api\GeneralController@asset');
Route::post('assetqty', 'Api\GeneralController@assetqty');
Route::post('jabatanqty', 'Api\GeneralController@jabatanqty');
Route::post('assetcabang', 'Api\GeneralController@assetcabang');
Route::post('assethis', 'Api\GeneralController@assethis');
Route::post('realisasi-personil', 'Api\GeneralController@team');
Route::post('teamasset', 'Api\GeneralController@teamasset');
Route::post('jabatan', 'Api\GeneralController@jabatan');
Route::post('tipeqty', 'Api\GeneralController@tipeqty');


Route::post('getproduct', 'Api\GeneralController@getproduct');


Route::post('master/usersupd', 'Api\GeneralController@getusersUpdTime');
Route::post('master/usersnew', 'Api\GeneralController@getusersNew');
Route::post('master/kliennew', 'Api\GeneralController@getKlienNew');
Route::post('master/klienupd', 'Api\GeneralController@getKlienUpdTime');
Route::post('master/karyawanupd', 'Api\GeneralController@getKaryawanUpdTime');
Route::post('master/karyawannew', 'Api\GeneralController@getKaryawanNew');
Route::post('master/cabangupd', 'Api\GeneralController@getcabangUpdTime');
Route::post('master/cabangnew', 'Api\GeneralController@getCabangNew');
Route::post('master/roleupd', 'Api\GeneralController@getroleUpdTime');
Route::post('master/rolenew', 'Api\GeneralController@getroleNew');
Route::post('master/cabangkaryawanupd', 'Api\GeneralController@getCabangKaryawanUpdTime');
Route::post('master/cabangkaryawannew', 'Api\GeneralController@getCabangKaryawanNew');


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
