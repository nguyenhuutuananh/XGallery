<?php

namespace App\Jobs;

use App\Oauth\Services\Flickr\Flickr;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class FlickrPhotoSizes
 * @package App\Jobs
 */
class FlickrPhotoSizes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private \App\Models\FlickrPhotos $photo;

    /**
     * Create a new job instance.
     *
     * @param  \App\Models\FlickrPhotos  $photo
     */
    public function __construct(\App\Models\FlickrPhotos $photo)
    {
        $this->photo = $photo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = app(Flickr::class);

        if (!$sizes = $client->get('photos.getSizes', ['photo_id' => $this->photo->id])) {
            return;
        }

        $this->photo->sizes = $sizes->sizes;
        $this->photo->save();
    }
}
