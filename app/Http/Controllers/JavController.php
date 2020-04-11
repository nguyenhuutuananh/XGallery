<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\HasJavMovies;
use App\JavGenres;
use App\JavIdols;
use App\JavMovies;
use App\JavMoviesXref;
use App\Jobs\JavDownload;
use App\MenuItems;
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
    use HasJavMovies;

    /**
     * @param  Request  $request
     * @return Application|Factory|View
     */
    public function dashboard(Request $request)
    {
        return view(
            'jav.index',
            [
                'items' => $this->getMovies($request),
                'sidebar' => MenuItems::all(),
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
                'sidebar' => MenuItems::all(),
                'title' => 'JAV movie - '.$movie->name,
                'description' => $movie->description
            ]
        );
    }

    public function genre(int $id, Request $request)
    {
        $movieIds = JavMoviesXref::where(['xref_id' => $id, 'xref_type' => 'genre'])->select('movie_id')->get();

        return view(
            'jav.index',
            [
                'items' => $this->getMovies($request, ['ids' => $movieIds->toArray()]),
                'sidebar' => MenuItems::all(),
                'title' => 'JAV genre - '.JavGenres::find($id)->first()->name,
                'description' => ''
            ]
        );
    }

    public function idol(int $id, Request $request)
    {
        return view(
            'jav.idol',
            [
                'items' => $this->getMovies(
                    $request,
                    [
                        'ids' => JavMoviesXref::where(['xref_id' => $id, 'xref_type' => 'idol'])
                            ->select('movie_id')->get()->toArray()
                    ]
                ),
                'idol' => JavIdols::find($id),
                'sidebar' => MenuItems::all(),
                'title' => 'JAV genre - '.JavGenres::find($id),
                'description' => ''
            ]
        );
    }

    /**
     * @param  Request  $request
     * @return Application|Factory|View
     */
    public function search(Request $request)
    {
        return view(
            'jav.index',
            [
                'items' => $this->getMovies($request),
                'sidebar' => MenuItems::all(),
                'title' => 'JAV movies - Searching by keyword - '.$request->get('keyword'),
                'description' => ''
            ]
        );
    }

    public function download(string $itemNumber)
    {
        JavDownload::dispatch($itemNumber)->onConnection('database');
    }
}
