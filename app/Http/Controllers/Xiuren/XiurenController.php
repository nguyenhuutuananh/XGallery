<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Http\Controllers\Xiuren;

use App\Http\Controllers\BaseController;
use App\Jobs\XiurenDownload;
use App\Xiuren;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

/**
 * Class XiurenController
 * @package App\Http\Controllers
 */
class XiurenController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function dashboard()
    {
        return view(
            'xiuren.index',
            [
                'items' => Xiuren::paginate(15),
                'sidebar' => $this->getMenuItems(),
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
