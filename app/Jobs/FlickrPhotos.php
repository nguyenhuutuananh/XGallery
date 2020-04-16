<?php

namespace App\Jobs;

use App\OAuth\Flickr;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class FlickrPhotos
 * @package App\Jobs
 */
class FlickrPhotos implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int    $page;
    private object $contact;

    /**
     * Create a new job instance.
     *
     * @param $contact
     * @param  int  $page
     */
    public function __construct(object $contact, int $page)
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

        if (!$photos = $flickr->get(
            'people.getPhotos',
            ['user_id' => $this->contact->nsid, 'page' => $this->page]
        )) {
            return;
        }

        foreach ($photos->photos->photo as $photo) {
            if ($item = \App\FlickrPhotos::where(['id' => $photo->id, 'owner' => $photo->owner])->first()) {
                continue;
            }

            $model      = app(\App\FlickrPhotos::class);
            $properties = get_object_vars($photo);

            foreach ($properties as $key => $value) {
                $model->{$key} = $value;
            }

            $model->save();
        }
    }
}
