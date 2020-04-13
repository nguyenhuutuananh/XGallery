<?php

namespace App\Jobs;

use App\OAuth\Flickr;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FlickrPhotos implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $page;
    private $contact;

    /**
     * Create a new job instance.
     *
     * @param  int  $page
     */
    public function __construct($contact, int $page)
    {
        $this->contact = $contact;
        $this->page    = $page;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $flickr = new Flickr();

        if (!$photos = $flickr->get([
            'method' => 'flickr.people.getPhotos', 'user_id' => $this->contact->nsid, 'page' => $this->page
        ])) {
            return;
        }

        foreach ($photos->photos->photo as $photo) {
            if ($item = \App\FlickrPhotos::where(['id' => $photo->id, 'owner' => $photo->owner])->first()) {
                continue;
            }

            $model = app(\App\FlickrPhotos::class);
            $properties = get_object_vars($photo);

            foreach ($properties as $key => $value) {
                $model->{$key} = $value;
            }

            $model->save();
        }
    }
}
