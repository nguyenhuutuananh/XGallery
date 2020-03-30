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

Route::namespace('Nhaccuatui')
    ->prefix('v1/nhaccuatui')
    ->group(function () {
        Route::get('/', 'IndexController@index')->name('get.nhaccuatui.index');
        Route::get('/songs', 'IndexController@getSongs')->name('get.nhaccuatui.songs');
        Route::put('/fetch', 'IndexController@fetchSongs')->name('put.nhaccuatui.songs');
    });
