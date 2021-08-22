<?php

use Illuminate\Support\Facades\Auth;
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

// BlackListed
Route::group(['domain' => 'redmine.trenbe.com'], function () {
    Route::get('{any}', function ($any) {
        return abort(404);
    })->where('any', '.*');
});

Route::get('/', 'HomeController@index')->name('home');

Route::group(['prefix' => 'admin'], function () {
    Auth::routes();
    Route::get('/', 'UploadController@index')->name('upload')->middleware('auth:web');
});
