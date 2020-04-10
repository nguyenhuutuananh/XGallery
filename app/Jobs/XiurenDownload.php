<?php

namespace App\Jobs;

use App\Xiuren;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Class XiurenDownload
 * @package App\Jobs
 */
class XiurenDownload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $id;

    /**
     * @var int Execute timeout
     */
    public int $timeout = 300;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $item    = Xiuren::find($this->id);
        $name = basename($item->url, '.html');
        $crawler = app(\App\Crawlers\Crawler\Xiuren::class);
        try {
            foreach ($item->images as $image) {
                if (!$crawler->download($image, 'xiuren' . DIRECTORY_SEPARATOR . $name)) {
                    Log::stack(['download'])->warning('Download error ' . $image);
                }
            }
        } catch (Exception $exception) {
            Log::stack(['download'])->error($exception->getMessage());
        }
    }
}
