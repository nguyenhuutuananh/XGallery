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
 * Class FlickrPhotos
 * @package App\Console\Commands\Flickr
 */
class FlickrPhotos extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flickr:photos {task=fully}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetching Flickr photos';

    public function fully()
    {
        $client = app(Flickr::class);
        if (!$contact = \App\Models\FlickrContacts::orderBy('updated_at', 'asc')->first()) {
            return;
        }

        $contact->touch();

        $this->output->title('Working on contact '.$contact->nsid);

        if (!$photos = $client->get('people.getPhotos', ['user_id' => $contact->nsid])) {
            return;
        }

        $this->output->note(
            'Got '.$photos->photos->total.' photos in '.$photos->photos->pages.' pages'
        );

        $this->createProgressBar($photos->photos->pages);

        // Trigger job to fetch photos of user
        for ($page = 1; $page <= $photos->photos->pages; $page++) {
            \App\Jobs\Flickr\FlickrPhotos::dispatch($contact, $page)->onQueue('flickr');
            $this->progressBar->advance();
        }

        return true;
    }
}
