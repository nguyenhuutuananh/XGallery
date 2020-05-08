<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Http\Traits;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Request;

/**
 * Trait HasModel
 * @package App\Http\Controllers\Traits
 */
trait HasModel
{
    private int      $itemsPerPage = 15;

    /**
     * @param  Request  $request
     * @param  array  $options
     * @return LengthAwarePaginator
     */
    protected function getItems(Request $request, array $options = [])
    {
        $model = $this->getModel()->orderBy(
            $request->get('sort-by', $this->sortBy['by']),
            $request->get('sort-dir', $this->sortBy['dir'])
        );

        if (isset($options['ids'])) {
            $model = $model->whereIn('id', $options['ids']);
        }

        if ($keyword = $request->get('keyword')) {
            $model = $model->where(function ($query) use ($keyword) {
                foreach ($this->filterFields as $filterField) {
                    $query = $query->orWhere($filterField, 'LIKE', '%'.$keyword.'%');
                }
            });
        }

        return $this->advanceSearch($model, $request, $options)->paginate((int) $request->get(
            'per-page',
            $this->itemsPerPage
        ));
    }

    /**
     * @return Model
     */
    protected function getModel(): Model
    {
        return app($this->modelClass);
    }

    protected function advanceSearch(Builder $builder, Request $request, array $options = [])
    {
        return $builder;
    }
}
