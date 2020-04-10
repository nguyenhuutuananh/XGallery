<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Http\Controllers;

use App\Jobs\XiurenDownload;
use App\MenuItems;
use App\Xiuren;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

class XiurenController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function dashboard()
    {
        return view(
            'xiuren.index',
            [
                'items' => Xiuren::paginate(15),
                'sidebar' => MenuItems::all(),
                'title' => 'Xiuren',
                'description' => ''
            ]
        );
    }

    public function download(string $id)
    {
        XiurenDownload::dispatch($id)->onConnection('database');
    }
}
