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
use Illuminate\Contracts\Redis\LimiterTimeoutException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

/**
 * Class FlickrPhotoSizes
 * @package App\Jobs\Flickr
 */
class FlickrPhotoSizes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use HasJob;

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
     * @throws LimiterTimeoutException
     */
    public function handle()
    {
        Redis::throttle('flickr')->allow(3000)->every(3600)->then(function () {
            $client = app(Flickr::class);

            if (!$sizes = $client->get('photos.getSizes', ['photo_id' => $this->photo->id])) {
                return;
            }

            $this->photo->sizes = $sizes->sizes;
            $this->photo->save();
        }, function () {
            // Release back to queue
            Log::stack(['queue'])->notice('Released job ' . __CLASS__, get_object_vars($this->photo));
            $this->release(3600);
        });
    }
}
