<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

/**
 * Class Truyenchon
 * @package App
 */
class Truyenchon extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'truyenchon';

    protected $fillable = [
        'url', 'cover', 'title'
    ];
}
