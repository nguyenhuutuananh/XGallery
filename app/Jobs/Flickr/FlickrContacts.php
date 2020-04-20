<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Jobs\Flickr;

use App\Jobs\Traits\HasJob;
use App\Oauth\Services\Flickr\Flickr;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Class FlickrContacts
 * @package App\Jobs\Flickr
 */
class FlickrContacts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use HasJob;

    private int $page;

    /**
     * Create a new job instance.
     *
     * @param  int  $page
     */
    public function __construct(int $page)
    {
        $this->page = $page;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = app(Flickr::class);

        if (!$contacts = $client->get('contacts.getList', ['page' => $this->page])) {
            return;
        }

        Log::debug('Got '.count($contacts->contacts->contact).' contact');

        foreach ($contacts->contacts->contact as $contact) {
            /**
             * @TODO Trigger sub job for flickr.people.getInfo
             */
            $model = app(\App\Models\FlickrContacts::class);
            if ($item = $model->where(['nsid' => $contact->nsid])->first()) {
                continue;
            }

            $properties = get_object_vars($contact);

            foreach ($properties as $key => $value) {
                $model->{$key} = $value;
            }

            $model->save();
        }
    }
}
