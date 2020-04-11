<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Http\Controllers\Traits;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Request;

/**
 * Trait HasModel
 * @package App\Http\Controllers\Traits
 */
trait HasModel
{
    private int      $itemsPerPage = 15;

    protected function getItems(Request $request, array $options = [])
    {
        $model = $this->getModel();
        $model = $model->orderBy($request->get('sort-by', $this->sortBy['by']), $this->sortBy['dir']);

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

        return $model->paginate((int)$request->get('per-page', $this->itemsPerPage));
    }

    protected function getModel(): Model
    {
        return app($this->modelClass);
    }
}
