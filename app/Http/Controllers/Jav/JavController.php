<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Http\Controllers\Jav;

use App\Http\Controllers\BaseController;
use App\JavIdols;
use App\JavMovies;
use App\JavMoviesXref;
use App\Models\JavDownload;
use App\Models\JavGenres;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class JavController
 * @package App\Http\Controllers
 */
class JavController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param  Request  $request
     * @return Application|Factory|View
     */
    public function dashboard(Request $request)
    {
        return view(
            'jav.index',
            [
                'items' => app(\App\Repositories\JavMovies::class)->getItems($request->request->all()),
                'sidebar' => $this->getMenuItems(),
                'title' => 'JAV movies',
                'description' => ''
            ]
        );
    }

    /**
     * @param  int  $id
     * @return Application|Factory|View
     */
    public function movie(int $id)
    {
        $movie = JavMovies::find($id);

        return view(
            'jav.movie',
            [
                'item' => $movie,
                'sidebar' => $this->getMenuItems(),
                'title' => 'JAV movie - '.$movie->name,
                'description' => $movie->description
            ]
        );
    }

    /**
     * @param  int  $id
     * @param  Request  $request
     * @return Application|Factory|View
     */
    public function genre(int $id, Request $request)
    {
        $filter = array_merge(
            $request->request->all(),
            [
                'ids' => JavMoviesXref::where([
                    'xref_id' => $id, 'xref_type' => 'genre'
                ])->select('movie_id')->get()->toArray()
            ]
        );

        return view(
            'jav.index',
            [
                'items' => app(\App\Repositories\JavMovies::class)->getItems($filter),
                'sidebar' => $this->getMenuItems(),
                'title' => 'JAV genre - '.JavGenres::find($id)->first()->name,
                'description' => ''
            ]
        );
    }

    /**
     * @param  int  $id
     * @param  Request  $request
     * @return Application|Factory|View
     */
    public function idol(int $id, Request $request)
    {
        $filter = array_merge(
            $request->request->all(),
            [
                'ids' => JavMoviesXref::where([
                    'xref_id' => $id, 'xref_type' => 'idol'
                ])->select('movie_id')->get()->toArray()
            ]
        );

        return view(
            'jav.idol',
            [
                'items' => app(\App\Repositories\JavMovies::class)->getItems($filter),
                'idol' => JavIdols::find($id),
                'sidebar' => $this->getMenuItems(),
                'title' => 'JAV genre - '.JavGenres::find($id),
                'description' => ''
            ]
        );
    }

    /**
     * Add to download
     * @param  string  $itemNumber
     */
    public function download(string $itemNumber)
    {
        if (JavDownload::where(['item_number' => $itemNumber])->first()) {
            return;
        }

        $model = app(JavDownload::class);
        $model->item_number = $itemNumber;
        $model->save();
    }
}
