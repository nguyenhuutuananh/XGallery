<?php

namespace App\Jobs;

use App\Oauth\Flickr;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class FlickrContacts
 * @package App\Jobs
 */
class FlickrContacts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        $flickr = app(Flickr::class);

        if (!$contacts = $flickr->get('contacts.getList', ['page' => $this->page])) {
            return;
        }

        foreach ($contacts->contacts->contact as $contact) {
            // Contact already exists
            if ($item = \App\FlickrContacts::where(['nsid' => $contact->nsid])->first()) {
                continue;
            }

            $model      = app(\App\FlickrContacts::class);
            $properties = get_object_vars($contact);

            foreach ($properties as $key => $value) {
                $model->{$key} = $value;
            }

            $model->save();
        }
    }
}
