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
use App\Jobs\Flickr\FlickrPhotoSizes;

/**
 * Class FlickrPhotosSizes
 * @package App\Console\Commands\Flickr
 */
class FlickrPhotosSizes extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flickr:photossizes {task=fully}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetching Flickr photos';

    public function fully()
    {
        if (!$photos = \App\Models\FlickrPhotos::where(['sizes' => null])->take(30)->get()) {
            return false;
        }

        $this->createProgressBar(count($photos));
        $this->progressBar->setMessage('Photos', 'message');

        foreach ($photos as $photo) {
            FlickrPhotoSizes::dispatch($photo);
            $this->progressBar->setMessage('<fg=yellow;options=bold>QUEUED</>', 'status');
            $this->progressBar->advance();
        }

        return true;
    }
}
