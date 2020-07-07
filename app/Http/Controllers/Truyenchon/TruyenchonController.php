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
use App\Jobs\Truyenchon\TruyenchonDownload;
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

    public function dashboard(Request $request)
    {
        return view(
            'truyenchon.index',
            [
                'items' => app(\App\Repositories\Truyenchon::class)->getItems($request->request->all()),
                'sidebar' => $this->getMenuItems(),
                'title' => 'Truyenchon',
                'description' => ''
            ]
        );
    }

    /**
     * @param  string  $id
     * @param  string  $chapter
     * @return Application|Factory|View
     */
    public function story(string $id, string $chapter)
    {
        $story = Truyenchon::find($id);
        $keys = array_keys($story->chapters);
        $keys = array_reverse($keys);
        $position = array_search($chapter, $keys);
        $nextKey = $keys[$position + 1] ?? 0;

        return view(
            'truyenchon.story',
            [
                'story' => $story,
                'items' => $story->chapters[$chapter],
                'next' => $nextKey,
                'sidebar' => $this->getMenuItems(),
                'title' => 'Truyenchon - '.$story->title,
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
