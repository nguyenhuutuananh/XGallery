<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Http\Controllers\Traits;

use App\JavMovies;
use Symfony\Component\HttpFoundation\Request;

trait HasJavMovies
{
    private $itemsPerPage = 5;

    protected function getMovies(Request $request, array $options = [])
    {
        $model = $this->getModel();
        $model = $model->orderBy($request->get('sort-by', 'release_date'), 'desc');

        if (isset($options['ids'])) {
            $model = $model->whereIn('id', $options['ids']);
        }

        if ($keyword = $request->get('keyword')) {
            $model = $model->where(function ($query) use ($keyword) {
                $query->orWhere('description', 'LIKE', '%'.$keyword.'%')
                    ->orWhere('item_number', 'LIKE', '%'.$keyword.'%')
                    ->orWhere('director', 'LIKE', '%'.$keyword.'%')
                    ->orWhere('studio', 'LIKE', '%'.$keyword.'%')
                    ->orWhere('label', 'LIKE', '%'.$keyword.'%')
                    ->orWhere('channel', 'LIKE', '%'.$keyword.'%')
                    ->orWhere('series', 'LIKE', '%'.$keyword.'%');
            });
        }

        return $model->paginate($request->get('per-page', $this->itemsPerPage));
    }

    protected function getModel(): JavMovies
    {
        return app(JavMovies::class);
    }
}
