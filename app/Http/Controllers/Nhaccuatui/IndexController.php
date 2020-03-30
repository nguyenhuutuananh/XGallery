<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Http\Controllers\Nhaccuatui;

use App\Http\Controllers\ApiController;
use App\Nhaccuatui;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class IndexController
 * @package App\Http\Controllers\Nhaccuatui
 */
class IndexController extends ApiController
{
    /**
     * @return Response
     */
    public function index()
    {
        return $this->apiSucceed(Nhaccuatui::all());
    }

    /**
     * @param  Request  $request
     * @return Response
     */
    public function getSongs(Request $request)
    {
        $model = app(Nhaccuatui::class);

        if ($title = $request->get('title')) {
            $model = $model->where('name', 'LIKE', '%'.$title.'%');
        }

        return $this->apiSucceed($model->get());
    }

    /**
     * @param  Request  $request
     */
    public function fetchSongs(Request $request)
    {
        $args = [];
        if ($title = $request->get('title')) {
            $args['--title'] = $title;
        }
        if ($singer = $request->get('singer')) {
            $args['--singer'] = $singer;
        }

        Artisan::queue('nhaccuatui', $args)->onConnection('database');
    }
}
