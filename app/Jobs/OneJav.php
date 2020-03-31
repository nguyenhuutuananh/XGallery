<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Jobs;

use App\JavMovies;
use App\Jobs\OneJav\UpdateJavGenres;
use App\Jobs\OneJav\UpdateJavIdols;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * This job handle process OneJAV to JavMovies and update related data: idols / genres
 * @package App\Jobs
 */
class OneJav implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $itemDetail;

    /**
     * OneJav constructor.
     * @param  array  $itemDetail
     */
    public function __construct(array $itemDetail)
    {
        $this->itemDetail = $itemDetail;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $model      = app(JavMovies::class);
        $itemNumber = $this->itemDetail['title'];

        // To store in JavMovies we use item_number as unique
        if (!$item = $model->where(['item_number' => $itemNumber])->first()) {
            $item = app(JavMovies::class);
        }

        $item->item_number     = $itemNumber;
        $item->release_date    = $this->itemDetail['date'];
        $item->is_downloadable = true;
        $item->description     = isset($this->itemDetail['description']) ? $this->itemDetail['description'] : null;
        $item->save();

        // Trigger job to update genres and xref
        UpdateJavGenres::dispatch($item, $this->itemDetail)->onConnection('database');
        // Trigger job to update idols and xref
        UpdateJavIdols::dispatch($item, $this->itemDetail)->onConnection('database');
    }
}
