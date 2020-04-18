<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
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

    private string $url;

    /**
     * Create a new job instance.
     *
     * @param  string  $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /**
         * Validate if item already in database
         * @var Model $item
         */
        if ($item = \App\Models\Batdongsan::where(['url' => $this->url])->first()) {
            $item->touch();
            return;
        }

        if (!$itemDetail = app(\App\Crawlers\Crawler\Batdongsan::class)->getItemDetail($this->url)) {
            return;
        }

        $model = app(\App\Models\Batdongsan::class);
        $data  = get_object_vars($itemDetail);

        // Can not use fill() because it will be required fillable properties
        foreach ($data as $key => $value) {
            $model->{$key} = $value;
        }

        $model->save();
    }
}
