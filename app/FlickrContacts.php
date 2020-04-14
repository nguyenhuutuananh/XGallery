<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App;

use App\Database\Mongodb;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class FlickrContacts
 * @package App
 */
class FlickrContacts extends Mongodb
{
    protected $collection = 'flickr_contacts';

    /**
     * Return photos collection of this contact
     * @return HasMany|\Jenssegers\Mongodb\Relations\HasMany
     */
    public function photos()
    {
        return $this->hasMany(FlickrPhotos::class, 'owner', 'nsid');
    }
}
