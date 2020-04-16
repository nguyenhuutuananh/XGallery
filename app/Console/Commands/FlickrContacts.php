<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Console\Commands;

use App\Console\BaseCommand;
use App\OAuth\Services\Flickr\Flickr;

/**
 * Class FlickrContacts
 * @package App\Console\Commands
 */
class FlickrContacts extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flickr:contacts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetching Flickr contacts';

    public function handle()
    {
        $flickr = app(Flickr::class);

        if (!$contacts = $flickr->get('contacts.getList')) {
            return;
        }

        for ($page = 1; $page <= $contacts->contacts->pages; $page++) {
            \App\Jobs\FlickrContacts::dispatch($page)->onConnection('database');
        }
    }
}
