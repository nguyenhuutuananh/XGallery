<?php

namespace App\Jobs;

use App\Crawlers\Crawler\Truyenchon;
use App\Jobs\Traits\HasJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class TruyenchonChapterDownload
 * @package App\Jobs
 */
class TruyenchonChapterDownload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use HasJob;

    private array  $images;
    private string $path;

    /**
     * Create a new job instance.
     *
     * @param  array  $images
     * @param  string  $path
     */
    public function __construct(array $images, string $path)
    {
        $this->images = $images;
        $this->path   = $path;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $crawler = app(Truyenchon::class);
        foreach ($this->images as $image) {
            $crawler->download(
                $image,
                'truyenchon'.$this->path
            );
        }
    }
}
