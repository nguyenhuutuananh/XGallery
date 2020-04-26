<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Jobs\Truyenchon;

use App\Jobs\Traits\HasJob;
use App\Models\Truyenchon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class Chapters
 * @package App\Jobs\Truyenchon
 */
class Chapters implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use HasJob;

    private array  $item;
    private string $chapterUrl;

    /**
     * Create a new job instance.
     *
     * @param  array  $item
     * @param  string  $chapterUrl
     */
    public function __construct(array $item, string $chapterUrl)
    {
        $this->item = $item;
        $this->chapterUrl = $chapterUrl;
    }

    public function handle()
    {
        if (!$item = Truyenchon::where(['url' => $this->item['url']])->first()) {
            return;
        }

        $chapter = explode('/', $this->chapterUrl);

        $crawler = app(\App\Crawlers\Crawler\Truyenchon::class);
        if (!$itemDetail = $crawler->getItemDetail($this->chapterUrl)) {
            return;
        }

        $item->{$chapter[5]} = $itemDetail->images->toArray();
        $item->save();
    }
}
