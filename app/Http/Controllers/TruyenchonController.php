<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Http\Controllers;

use App\MenuItems;
use App\Truyenchon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

/**
 * Class TruyenchonController
 * @package App\Http\Controllers
 */
class TruyenchonController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function dashboard()
    {
        return view(
            'truyenchon.index',
            [
                'items' => Truyenchon::paginate(15),
                'sidebar' => MenuItems::all(),
                'title' => 'Truyenchon',
                'description' => ''
            ]
        );
    }

    public function download(string $id)
    {
        //XiurenDownload::dispatch($id)->onConnection('database');
    }
}
