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

Route::post('/upload', 'UploadController@apiUploadAjax')->name('json.fileupload');
Route::post('/get-data', 'HomeController@apiGetData')->name('get.data');
Route::post('/get-csv', 'HomeController@apiGetCsv')->name('get.csv');
Route::any('/delete-item', 'HomeController@deleteItem')->name('item.delete');
