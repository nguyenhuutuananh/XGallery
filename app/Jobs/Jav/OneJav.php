<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Jobs\Jav;

use App\Jobs\Middleware\RateLimited;
use App\Jobs\Queues;
use App\Jobs\Traits\HasJob;
use App\Models\JavMovies;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * This job handle process OneJAV to JavMovies and update related data: idols / genres
 * @package App\Jobs\Jav
 */
class OneJav implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use HasJob;

    /**
     * @var array Item information by array format because we won't use getItemDetail
     */
    private array $itemDetail;

    /**
     * OneJav constructor.
     * @param  array  $itemDetail
     */
    public function __construct(array $itemDetail)
    {
        $this->itemDetail = $itemDetail;
        $this->onQueue(Queues::QUEUE_JAV);
    }

    /**
     * @return RateLimited[]
     */
    public function middleware()
    {
        return [new RateLimited('onejav')];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $model = app(JavMovies::class);
        $itemNumber = $this->itemDetail['title'];

        // To store in JavMovies we use item_number as unique
        if (!$movie = $model->where(['item_number' => $itemNumber])->first()) {
            // Not found than create new model
            $movie = app(JavMovies::class);
            Log::stack(['jav'])->info('New video', $this->itemDetail);
        }

        $movie->item_number = $itemNumber;
        $movie->release_date = $this->itemDetail['date'];
        $movie->is_downloadable = true;
        $movie->description = isset($this->itemDetail['description']) ? $this->itemDetail['description'] : null;
        $movie->save();

        // Trigger job to update genres and xref
        UpdateGenres::dispatch($movie, $this->itemDetail['tags']);
        // Trigger job to update idols and xref
        UpdateIdols::dispatch($movie, $this->itemDetail['actresses']);
    }
}
