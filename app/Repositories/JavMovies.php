<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Repositories;

use App\Models\JavMoviesXref;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Class JavMovies
 * @package App\Repositories
 */
class JavMovies extends BaseRepository
{
    private array $filterFields = [
        'name', 'item_number', 'content_id', 'dvd_id', 'director', 'studio', 'label', 'channel', 'series', 'description'
    ];

    public function __construct(\App\Models\JavMovies $model)
    {
        parent::__construct($model);
    }

    /**
     * @param  array  $filter
     * @return LengthAwarePaginator
     */
    public function getItems(array $filter = [])
    {
        if (isset($filter['keyword']) && !empty($filter['keyword'])) {
            $filter['searchBy'] = $filter['searchBy'] ?? 'keyword';
            switch ($filter['searchBy']) {
                case 'keyword':
                    $this->builder->where(function ($query) use ($filter) {
                        foreach ($this->filterFields as $filterField) {
                            $query = $query->orWhere($filterField, 'LIKE', '%'.$filter['keyword'].'%');
                        }
                    });
                    break;
                default:
                    $this->builder->where($filter['searchBy'], 'LIKE', '%'.$filter['keyword'].'%');
                    break;
            }
        }

        if (isset($filter['genre']) && !empty($filter['genre'])) {
            $ids = JavMoviesXref::where(['xref_id' => $filter['genre'], 'xref_type' => 'genre'])
                ->select('movie_id')
                ->get()->toArray();
            $this->builder->whereIn('id', $ids);
        }

        if (isset($filter['idol']) && !empty($filter['idol'])) {
            $ids = JavMoviesXref::where(['xref_id' => $filter['idol'], 'xref_type' => 'idol'])
                ->select('movie_id')
                ->get()->toArray();
            $this->builder->whereIn('id', $ids);
        }

        return parent::getItems($filter);
    }
}
