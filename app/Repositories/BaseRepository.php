<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    protected Model $model;
    protected Builder                $builder;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->builder = $model->query();
    }

    public function getItems(array $filter = [])
    {
        if (isset($filter['sort-by'])) {
            $this->builder->orderBy($filter['sort-by'], $filter['sort-dir'] ?? 'asc');
        }

        return $this->builder->paginate(isset($filter['per-page']) ? (int) $filter['per-page'] : 15)
            ->appends(request()->except('page'));
    }
}
