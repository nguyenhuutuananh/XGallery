<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\HasMenu;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Storage;

/**
 * Class Controller
 * @package App\Http\Controllers
 */
class DashboardController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use HasMenu;

    public function index()
    {
        $currentCrawling = [
            'onejav' => $this->getCount('onejav'),
            'r18' => $this->getCount('r18'),
            'xcity' => $this->getCount('xcityprofile').' / '.$this->getCount('xcityvideo')
        ];


        return view(
            'dashboard.index',
            [
                'sidebar' => $this->getMenuItems(),
                'currentCrawling' => $currentCrawling,
                'title' => 'Dashboard',
                'description' => ''
            ]
        );
    }

    private function getCount(string $name)
    {
        $tmpFile = strtolower($name.'.tmp');
        if (Storage::disk('local')->exists($tmpFile)) {
            return explode(':', Storage::disk('local')->get($tmpFile))[0];
        }

        return 1;
    }
}
