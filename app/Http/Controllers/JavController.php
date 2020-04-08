<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Http\Controllers;

use App\JavMovies;
use App\MenuItems;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

/**
 * Class JavController
 * @package App\Http\Controllers
 */
class JavController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index()
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
}
