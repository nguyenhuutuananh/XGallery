<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Jobs\Flickr;

use App\Crawlers\HttpClient;
use App\Jobs\Middleware\FlickrRateLimited;
use App\Jobs\Queues;
use App\Jobs\Traits\HasJob;
use App\Oauth\Services\Flickr\Flickr;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Class FlickrDownload
 * @package App\Jobs\Flickr
 */
class FlickrDownload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use HasJob;

    private string    $owner;
    private object    $photo;

    /**
     * Create a new job instance.
     *
     * @param  object  $photo
     */
    public function __construct(string $owner, object $photo)
    {
        $this->owner = $owner;
        $this->photo = $photo;
        $this->onQueue(Queues::QUEUE_FLICKR);
    }

    /**
     * @return FlickrRateLimited[]
     */
    public function middleware()
    {
        return [new FlickrRateLimited()];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = app(Flickr::class);
        $httpClient = app(HttpClient::class);

        if (!$sizes = $client->get('photos.getSizes', ['photo_id' => $this->photo->id])) {
            return;
        }

        $size = end($sizes->sizes->size);
        if (!$filePath = $httpClient->download($size->source, 'flickr'.DIRECTORY_SEPARATOR.$this->owner)) {
            return;
        }

        $process = new Process([base_path().'/gdrive.sh']);
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        Storage::delete($filePath);
    }
}
