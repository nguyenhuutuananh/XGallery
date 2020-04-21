<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\BaseController;
use App\Models\CrawlerEndpoints;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\View\View;

/**
 * Class Controller
 * @package App\Http\Controllers
 */
class DashboardController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @return Application|Factory|View
     */
    public function dashboard()
    {
        return view(
            'dashboard.index',
            [
                'sidebar' => $this->getMenuItems(),
                'endpoints' => CrawlerEndpoints::all(),
                'title' => 'Dashboard',
                'description' => ''
            ]
        );
    }
}
