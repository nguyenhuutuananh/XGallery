<?php

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

Route::get('/', ['App\Http\Controllers\DashboardController', 'index']);
Route::prefix('jav')
    ->group(function () {
        Route::get('/', ['App\Http\Controllers\JavController', 'dashboard']);
        Route::get('/movie/{id}', ['App\Http\Controllers\JavController', 'movie'])->name('movie.view');
        Route::get('/genre/{id}', ['App\Http\Controllers\JavController', 'genre'])->name('genre.view');
        Route::get('/idol/{id}', ['App\Http\Controllers\JavController', 'idol'])->name('idol.view');
        Route::get('/search', ['App\Http\Controllers\JavController', 'search'])->name('search.view');
        Route::get('/download/{itemNumber}', ['App\Http\Controllers\JavController', 'download'])->name('download.request');
    });

Route::get('login/flickr', 'Auth\FlickrController@redirectToProvider');
Route::get('login/flickr/callback', 'Auth\FlickrController@handleProviderCallback');
Route::get('/debug-sentry', function () {
    throw new Exception('My first Sentry error!');
});
