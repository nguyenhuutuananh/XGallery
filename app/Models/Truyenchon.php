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
 * Class Truyenchon
 * @package App\Models
 */
class Truyenchon extends Mongodb
{
    public $collection = 'truyenchon';

    /**
     * @param  int  $holderSize
     * @return string
     */
    public function getCover(int $holderSize = 350): string
    {
        if (empty($this->cover) || !config('adult.cover')) {
            return 'https://via.placeholder.com/' . $holderSize;
        }

        return $this->cover;
    }
}
