<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Http\Controllers\Flickr;

use App\FlickrContacts;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Traits\HasMenu;
use App\Http\Controllers\Traits\HasModel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FlickrController
 * @package App\Http\Controllers\Flickr
 */
class FlickrController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use HasModel;
    use HasMenu;

    protected string $modelClass   = FlickrContacts::class;
    protected array  $sortBy       = ['by' => 'id', 'dir' => 'desc'];
    protected array  $filterFields = [

    ];

    /**
     * @param  Request  $request
     * @return Application|Factory|View
     */
    public function dashboard(Request $request)
    {
        return view(
            'flickr.index',
            [
                'items' => $this->getItems($request),
                'sidebar' => $this->getMenuItems(),
                'title' => 'Flickr',
                'description' => ''
            ]
        );
    }

    public function contact(string $nsid)
    {
        return view(
            'flickr.photos',
            [
                'items' => FlickrContacts::where(['nsid' => $nsid])->first()->photos()->paginate(30),
                'sidebar' => $this->getMenuItems(),
                'title' => 'Flickr',
                'description' => ''
            ]
        );
    }
}
