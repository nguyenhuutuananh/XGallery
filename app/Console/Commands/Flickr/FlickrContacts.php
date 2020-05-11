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
    protected $signature = 'flickr:contacts {task=fully}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetching Flickr contacts';

    public function fully()
    {
        if (!$contacts = app(Flickr::class)->get('contacts.getList')) {
            return false;
        }

        $this->output->note(
            'Got '.count($contacts->contacts->contact).' contacts in '.$contacts->contacts->pages.' pages'
        );

        $this->createProgressBar($contacts->contacts->pages);

        for ($page = 1; $page <= $contacts->contacts->pages; $page++) {
            // Add contacts on a page
            \App\Jobs\Flickr\FlickrContacts::dispatch($page)->onQueue('flickr');
            $this->progressBar->advance();
        }

        return true;
    }
}
