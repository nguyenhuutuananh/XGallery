<?php

namespace App\Listeners;

use App\Events\HttpResponded;

class ValidateHttpRespond
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  HttpResponded  $event
     * @return void
     */
    public function handle(HttpResponded $event)
    {
    }
}
