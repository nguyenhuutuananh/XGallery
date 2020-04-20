<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Http\Controllers\Truyenchon;

use App\Http\Controllers\BaseController;
use App\Jobs\TruyenchonDownload;
use App\Models\Truyenchon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TruyenchonController
 * @package App\Http\Controllers
 */
class TruyenchonController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected string $modelClass   = Truyenchon::class;
    protected array  $sortBy       = ['by' => 'id', 'dir' => 'desc'];
    protected array  $filterFields = [
        'title'
    ];

    public function dashboard(Request $request)
    {
        return view(
            'truyenchon.index',
            [
                'items' => $this->getItems($request),
                'sidebar' => $this->getMenuItems(),
                'title' => 'Truyenchon',
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
            'truyenchon.index',
            [
                'items' => $this->getItems($request),
                'sidebar' => $this->getMenuItems(),
                'title' => 'Truyenchon - Searching by keyword - '.$request->get('keyword'),
                'description' => ''
            ]
        );
    }

    /**
     * @param  string  $id
     */
    public function download(string $id)
    {
        TruyenchonDownload::dispatch($id);
    }
}
