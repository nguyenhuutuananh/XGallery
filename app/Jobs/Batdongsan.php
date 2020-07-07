<?php

namespace App\Jobs;

use App\Jobs\Middleware\RateLimited;
use App\Jobs\Traits\HasJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class Batdongsan
 * @package App\Jobs
 */
class Batdongsan implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use HasJob;

    private string $url;

    /**
     * Create a new job instance.
     *
     * @param  string  $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
        $this->onQueue(Queues::QUEUE_BATDONGSAN);
    }

    /**
     * @return RateLimited[]
     */
    public function middleware()
    {
        return [new RateLimited('batdongsan')];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $model = app(\App\Models\Batdongsan::class);
        /**
         * Validate if item already in database
         * @var \App\Models\Batdongsan $item
         */
        if ($item = $model->getItemByUrl($this->url)) {
            $item->touch();
            return;
        }

        if (!$itemDetail = app(\App\Crawlers\Crawler\Batdongsan::class)->getItemDetail($this->url)) {
            return;
        }

        $data = get_object_vars($itemDetail);

        // Can not use fill() because it will be required fillable properties
        foreach ($data as $key => $value) {
            $model->{$key} = $value;
        }

        $model->save();
    }
}
