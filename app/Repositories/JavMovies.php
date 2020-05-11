<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class JavMovies
 * @package App\Repositories
 */
class JavMovies
{
    private \App\JavMovies $model;
    private Builder        $builder;

    private array $filterFields = [
        'name', 'item_number', 'content_id', 'dvd_id', 'director', 'studio', 'label', 'channel', 'series', 'description'
    ];

    public function __construct(\App\JavMovies $model)
    {
        $this->model = $model;
        $this->builder = $model->query();
    }

    /**
     * @param  array  $filter
     * @return LengthAwarePaginator
     */
    public function getItems(array $filter = [])
    {
        if (isset($filter['sort-by'])) {
            $this->builder->orderBy($filter['sort-by'], $filter['sort-dir'] ?? 'asc');
        }

        if (isset($filter['keyword'])) {
            $this->builder->where(function ($query) use ($filter) {
                foreach ($this->filterFields as $filterField) {
                    $query = $query->orWhere($filterField, 'LIKE', '%'.$filter['keyword'].'%');
                }
            });
        }

        if (isset($filter['ids'])) {
            $this->builder->whereIn('id', $filter['ids']);
        }

        if (isset($filter['director'])) {
            $this->builder->where('director', 'LIKE', '%'.$filter['director'].'%');
        }

        if (isset($filter['studio'])) {
            $this->builder->where('studio', 'LIKE', '%'.$filter['studio'].'%');
        }

        if (isset($filter['label'])) {
            $this->builder->where('label', 'LIKE', '%'.$filter['label'].'%');
        }

        return $this->builder->paginate($filter['limit'] ?? 15);
    }
}
