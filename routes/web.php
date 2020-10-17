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

Route::get('/', 'DemoController@home');
Route::get('/image', 'DemoController@home');

Route::post('/image', 'DemoController@uploadImage');
Route::get('/delete/{resource_id}', 'DemoController@deleteImage');
Route::get('/delete_all', 'DemoController@deleteAll');
Route::get('/cloud_image/{resource_key}', 'DemoController@cloudImage');
