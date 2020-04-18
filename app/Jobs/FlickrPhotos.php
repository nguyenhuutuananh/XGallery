<?php

namespace App\Jobs;

use App\Oauth\Services\Flickr\Flickr;
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
        $client = app(Flickr::class);

        if (!$photos = $client->get(
            'people.getPhotos',
            ['user_id' => $this->contact->nsid, 'page' => $this->page]
        )) {
            return;
        }

        foreach ($photos->photos->photo as $photo) {
            $model = app(\App\Models\FlickrPhotos::class);
            if ($item = $model->where(['id' => $photo->id, 'owner' => $photo->owner])->first()) {
                continue;
            }

            $properties = get_object_vars($photo);

            foreach ($properties as $key => $value) {
                $model->{$key} = $value;
            }

            $model->save();
        }
    }
}
