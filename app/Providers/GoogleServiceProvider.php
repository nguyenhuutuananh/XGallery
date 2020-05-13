<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Providers;

use Google_Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

/**
 * Class GoogleServiceProvider
 * @package App\Providers
 */
class GoogleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Google_Client:: class, function ($app) {
            $client = new   Google_Client();
            Storage::disk('local')
                ->put(
                    'client_secret_7096753146-memucg956hbii78oi9ai84kplb578h7l.apps.googleusercontent.com.json',
                    json_encode([
                        'web' => config('services.google')
                    ])
                );
            $client->setAuthConfig(Storage::path('client_secret_7096753146-memucg956hbii78oi9ai84kplb578h7l.apps.googleusercontent.com.json'));
            return $client;
        });
    }
}
