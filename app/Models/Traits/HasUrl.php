<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Models\Traits;

trait HasUrl
{
    /**
     * @param  string  $url
     * @return mixed
     */
    public function getItemByUrl(string $url)
    {
        return $this->where(['url' => $url])->first();
    }
}
