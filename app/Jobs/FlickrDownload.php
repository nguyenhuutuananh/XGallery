<?php

namespace App\Jobs;

use App\Crawlers\HttpClient;
use App\Oauth\Services\Flickr\Flickr;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class FlickrDownload
 * @package App\Jobs
 */
class FlickrDownload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int        $timeout = 300;
    private string $owner;
    private object $photo;

    /**
     * Create a new job instance.
     *
     * @param  object  $photo
     */
    public function __construct(string $owner, object $photo)
    {
        $this->owner = $owner;
        $this->photo = $photo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $flickrClient = app(Flickr::class);
        $httpClient   = app(HttpClient::class);

        if (!$sizes = $flickrClient->get('photos.getSizes', ['photo_id' => $this->photo->id])) {
            return;
        }

        $size = end($sizes->sizes->size);
        $httpClient->download($size->source, 'flickr'.DIRECTORY_SEPARATOR.$this->owner);
    }
}
