<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class Truyenchon
 * @package App\Repositories
 */
class Truyenchon extends BaseRepository
{
    public function __construct(\App\Models\Truyenchon $model)
    {
        parent::__construct($model);
    }

    public function getItems(array $filter = [])
    {
        if (isset($filter['keyword']) && !empty($filter['keyword'])) {
            $this->builder->where('title', 'LIKE', '%'.$filter['keyword'].'%');
        }

        return parent::getItems($filter);
    }
}
