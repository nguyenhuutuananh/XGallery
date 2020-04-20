<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/**
 * Class Kernel
 * @package App\Console
 */
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /**
         * Schedule with daily
         */

        $schedule->command('onejav daily')
            ->daily()
            ->withoutOverlapping()->runInBackground();
        //->emailOutputOnFailure(config('mail.to'));

        $schedule->command('flickr:contacts')
            ->daily()
            ->withoutOverlapping()->runInBackground();

        $schedule->command('queue:restart')
            ->daily()
            ->withoutOverlapping();
        $schedule->command('queue:retry all')
            ->daily()
            ->withoutOverlapping();

        /**
         * Schedule everyMinute
         */

        $schedule->command('batdongsan')
            ->everyMinute()
            ->withoutOverlapping()->runInBackground();

        $schedule->command('truyenchon fully')
            ->everyMinute()
            ->withoutOverlapping()->runInBackground();
        //->emailOutputOnFailure(config('mail.to'));

        /**
         * Schedule everyFiveMinutes
         */

        $schedule->command('onejav fully')
            ->everyFiveMinutes()
            ->withoutOverlapping()->runInBackground();
        //->emailOutputOnFailure(config('mail.to'));

        $schedule->command('flickr:photos')
            ->everyFiveMinutes()
            ->withoutOverlapping()->runInBackground();
        $schedule->command('flickr:photossizes')
            ->everyFiveMinutes()
            ->withoutOverlapping()->runInBackground();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
