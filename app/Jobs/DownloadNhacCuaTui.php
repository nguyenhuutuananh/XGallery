<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Jobs;

use App\Crawlers\Crawler\Nhaccuatui;
use App\Jobs\Traits\HasJob;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class DownloadNhacCuaTui
 * @package App\Jobs
 */
class DownloadNhacCuaTui implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use HasJob;

    private string $url;

    /**
     * DownloadNhacCuaTui constructor.
     * @param  string  $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        $crawler = app(Nhaccuatui::class);

        if (!$itemDetail = $crawler->getItemDetail($this->url)) {
            throw new Exception('URL not found');
        }

        $crawler->download($itemDetail->download, 'nhaccuatui');
    }
}
