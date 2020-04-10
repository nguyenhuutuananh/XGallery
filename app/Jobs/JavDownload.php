<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class JavDownload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $itemNumber;

    /**
     * Create a new job instance.
     *
     * @param  string  $itemNumber
     */
    public function __construct(string $itemNumber)
    {
        $this->itemNumber = $itemNumber;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $crawler = app(\App\Crawlers\Crawler\Onejav::class);
        $pages   = $crawler->search([$this->itemNumber]);
        $pages->each(function ($page) use ($crawler) {
            $item       = $page->sortByDesc('size')->first();
            $itemDetail = $crawler->getItemDetail($item['url']);
            $crawler->download($itemDetail->torrent, 'onejav');
        });
    }
}
