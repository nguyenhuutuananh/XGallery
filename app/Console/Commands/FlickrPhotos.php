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
 * Class FlickrPhotos
 * @package App\Console\Commands
 */
class FlickrPhotos extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flickr:photos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetching Flickr photos';

    public function handle()
    {
        $flickr  = app(Flickr::class);
        $contact = \App\FlickrContacts::orderBy('updated_at', 'asc')->first();
        $contact->touch();

        if (!$photos = $flickr->get('people.getPhotos', ['user_id' => $contact->nsid])) {
            return;
        }

        // Trigger job to fetch photos of user
        for ($page = 1; $page <= $photos->photos->pages; $page++) {
            \App\Jobs\FlickrPhotos::dispatch($contact, $page)->onConnection('database');
        }
    }
}
