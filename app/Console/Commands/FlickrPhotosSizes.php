<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Console\Commands;

use App\Console\BaseCommand;
use App\OAuth\Flickr;

/**
 * Class FlickrPhotosSizes
 * @package App\Console\Commands
 */
class FlickrPhotosSizes extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flickr:photossizes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetching Flickr photos';

    public function handle()
    {
        $flickr = app(Flickr::class);
        $photos = \App\FlickrPhotos::where(['sizes' => null])->take(30)->get();

        foreach ($photos as $photo) {
            if (!$sizes = $flickr->get('photos.getSizes', ['photo_id' => $photo->id])) {
                continue;
            }
            $photo->sizes = $sizes->sizes;
            $photo->save();
        }
    }
}
