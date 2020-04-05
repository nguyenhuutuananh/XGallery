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
use App\Jobs\Jav\UpdateGenres;
use App\Jobs\Jav\UpdateIdols;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Class R18
 * @package App\Jobs
 */
class R18 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $item;

    /**
     * R18 constructor.
     * @param  array  $item
     */
    public function __construct(array $item)
    {
        $this->item = $item;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!$itemDetail = app(\App\Services\Crawler\R18::class)->getItemDetail($this->item['url'])) {
            return;
        }

        // This movie is not released yet. Then just skip it
        if (empty($itemDetail->dvd_id)) {
            return;
        }

        $model = app(JavMovies::class);
        if (!$movie = $model->where(['item_number' => $itemDetail->dvd_id])->first()) {
            Log::stack(['jav'])->info('Saving new video', get_object_vars($itemDetail));
            $movie = app(JavMovies::class);
        }

        $movie->reference_url = $itemDetail->url;
        $movie->cover         = $itemDetail->cover;
        $movie->name          = $itemDetail->name;
        $movie->release_date  = $itemDetail->release_date;
        $movie->director      = $itemDetail->director;
        $movie->studio        = $itemDetail->studio;
        $movie->label         = $itemDetail->label;
        $movie->channel       = $itemDetail->channel;
        $movie->item_number   = strtoupper($itemDetail->dvd_id);
        $movie->content_id    = $itemDetail->content_id;
        $movie->dvd_id        = $itemDetail->dvd_id;
        $movie->series        = $itemDetail->series;
        $movie->gallery       = json_encode($itemDetail->gallery);
        $movie->sample        = isset($itemDetail->sample) ? $itemDetail->sample : null;

        $movie->save();

        // Trigger job to update genres and xref
        UpdateGenres::dispatch($movie, $itemDetail->categories)->onConnection('database');
        // Trigger job to update idols and xref
        UpdateIdols::dispatch($movie, $itemDetail->actress)->onConnection('database');
    }
}
