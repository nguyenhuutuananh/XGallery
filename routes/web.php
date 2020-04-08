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
        Route::get('/', ['App\Http\Controllers\JavController', 'index']);
        Route::get('/movie/{id}', ['App\Http\Controllers\JavController', 'index'])->name('movie.view');
    });

Route::get('login/flickr', 'Auth\FlickrController@redirectToProvider');
Route::get('login/flickr/callback', 'Auth\FlickrController@handleProviderCallback');
Route::get('/debug-sentry', function () {
    throw new Exception('My first Sentry error!');
});
