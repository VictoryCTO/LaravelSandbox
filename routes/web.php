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

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/scratch', function () {
    return view('scratch');
});

Route::get('/upload', 'ImageUploadController@showForm');

Route::post('/process', 'ImageUploadController@processForm');

Route::get('/display', 'ImageDisplayController@displayImages')->name('display');
