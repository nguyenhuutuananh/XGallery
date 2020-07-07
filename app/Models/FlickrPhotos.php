<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Models;

use App\Database\Mongodb;

/**
 * Class FlickrPhotos
 * @package App\Models
 */
class FlickrPhotos extends Mongodb
{
    protected $collection = 'flickr_photos';

    /**
     * @var mixed
     */
    private $sizes;

    /**
     * @return string
     */
    public function getCover(): string
    {
        if (!$this->sizes) {
            return '';
        }

        $size = array_slice($this->sizes['size'], 1, 1, true);
        $size = reset($size);

        return $size['source'];
    }
}
