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
use App\Models\Traits\HasUrl;

/**
 * Class Nhaccuatui
 * @package App\Models
 */
class Nhaccuatui extends Mongodb
{
    use HasUrl;

    public $collection = 'nhaccuatui';
}
