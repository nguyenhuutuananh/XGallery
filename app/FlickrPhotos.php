<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

/**
 * Class FlickrPhotos
 * @package App
 */
class FlickrPhotos extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'flickr_photos';
}
