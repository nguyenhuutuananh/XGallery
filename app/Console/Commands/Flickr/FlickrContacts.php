<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Console\Commands\Flickr;

use App\Console\BaseCommand;
use App\Oauth\Services\Flickr\Flickr;

/**
 * Class FlickrContacts
 * @package App\Console\Commands\Flickr
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
        if (!$contacts = app(Flickr::class)->get('contacts.getList')) {
            return;
        }

        for ($page = 1; $page <= $contacts->contacts->pages; $page++) {
            // Add contacts on a page
            \App\Jobs\Flickr\FlickrContacts::dispatch($page)->onQueue('flickr');
        }
    }
}
