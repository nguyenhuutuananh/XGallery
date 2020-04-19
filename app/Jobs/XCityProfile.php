<?php

namespace App\Jobs;

use App\JavIdols;
use App\Jobs\Traits\HasJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class XCityProfile
 * @package App\Jobs
 */
class XCityProfile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use HasJob;

    protected array $item;

    /**
     * Create a new job instance.
     *
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
        if (!$itemDetail = app(\App\Crawlers\Crawler\XCityProfile::class)->getItemDetail($this->item['url'])) {
            $this->release(900); // 15 minutes
            return;
        }

        $model = app(JavIdols::class);
        if (!$item = $model->where(['reference_url' => $itemDetail->url])->first()) {
            $item = app(JavIdols::class);
        }

        $item->name          = $itemDetail->name;
        $item->reference_url = $itemDetail->url;
        $item->cover         = $itemDetail->cover;
        $item->favorite      = $itemDetail->favorite ?? null;
        $item->birthday      = $itemDetail->birthday ?? null;
        $item->blood_type    = $itemDetail->blood_type ?? null;
        $item->city          = $itemDetail->city ?? null;
        $item->height        = $itemDetail->height ?? null;
        $item->breast        = $itemDetail->breast ?? null;
        $item->waist         = $itemDetail->waist ?? null;
        $item->hips          = $itemDetail->hips ?? null;

        $item->save();
    }
}
