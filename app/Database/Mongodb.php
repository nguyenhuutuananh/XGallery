<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Database;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

/**
 * Class Mongodb
 * @package App\Database
 */
class Mongodb extends Eloquent
{
    protected $connection = 'mongodb';
}
