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
        Route::get('/', ['App\Http\Controllers\JavController', 'dashboard'])->name('jav.index.view');
        Route::get('/movie/{id}', ['App\Http\Controllers\JavController', 'movie'])->name('movie.view');
        Route::get('/genre/{id}', ['App\Http\Controllers\JavController', 'genre'])->name('genre.view');
        Route::get('/idol/{id}', ['App\Http\Controllers\JavController', 'idol'])->name('idol.view');
        Route::post('/search', ['App\Http\Controllers\JavController', 'search'])->name('jav.search.view');
        Route::post(
            '/download/{itemNumber}',
            ['App\Http\Controllers\JavController', 'download']
        )->name('download.request');
    });

Route::prefix('xiuren')
    ->group(function () {
        Route::get('/', ['App\Http\Controllers\XiurenController', 'dashboard']);
        Route::post(
            '/download/{id}',
            ['App\Http\Controllers\XiurenController', 'download']
        )->name('xiuren.download.request');
    });

Route::prefix('truyenchon')
    ->group(function () {
        Route::get('/', ['App\Http\Controllers\TruyenchonController', 'dashboard'])->name('truyenchon.index.view');
        Route::post('/search', ['App\Http\Controllers\TruyenchonController', 'search'])->name('truyenchon.search.view');
        Route::post(
            '/download/{id}',
            ['App\Http\Controllers\TruyenchonController', 'download']
        )->name('truyenchon.download.request');
    });

Route::prefix('oauth')
    ->group(function () {
        Route::get('flickr', ['App\Http\Controllers\Auth\FlickrController', 'login']);
        Route::get('flickr/callback', ['App\Http\Controllers\Auth\FlickrController', 'callback']);
        Route::get('flickr/user', ['App\Http\Controllers\Auth\FlickrController', 'user']);
    });
