<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Http\Controllers;

use App\JavGenres;
use App\JavMovies;
use App\JavMoviesXref;
use App\MenuItems;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class JavController
 * @package App\Http\Controllers
 */
class JavController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function dashboard()
    {
        return view(
            'jav.index',
            [
                'items' => JavMovies::paginate(15),
                'sidebar' => MenuItems::all(),
                'title' => 'JAV movies',
                'description' => ''
            ]
        );
    }

    public function genre(int $id)
    {
        $movieIds = JavMoviesXref::where(['xref_id' => $id, 'xref_type' => 'genre'])->select('movie_id')->get();

        return view(
            'jav.index',
            [
                'items' => JavMovies::whereIn('id', $movieIds->toArray())->paginate(15),
                'sidebar' => MenuItems::all(),
                'title' => 'JAV genre - ' . JavGenres::find($id)->first()->name ,
                'description' => ''
            ]
        );
    }

    public function idol(int $id)
    {
        $movieIds = JavMoviesXref::where(['xref_id' => $id, 'xref_type' => 'idol'])->select('movie_id')->get();

        return view(
            'jav.index',
            [
                'items' => JavMovies::whereIn('id', $movieIds->toArray())->paginate(15),
                'sidebar' => MenuItems::all(),
                'title' => 'JAV genre - ' . JavGenres::find($id) ,
                'description' => ''
            ]
        );
    }

    public function search(Request $request)
    {
        $model = app(JavMovies::class);
        if ($keyword = $request->get('keyword')) {
            $model = $model->where(function ($query) use ($keyword) {
                $query->orWhere('description', 'LIKE', '%'.$keyword.'%')
                    ->orWhere('item_number', 'LIKE', '%'.$keyword.'%');
            });
        }

        return view(
            'jav.index',
            [
                'items' => $model->paginate(15),
                'sidebar' => MenuItems::all(),
                'title' => 'JAV movies',
                'description' => ''
            ]
        );
    }
}
