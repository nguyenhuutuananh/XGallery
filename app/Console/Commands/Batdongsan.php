<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Console\Commands;

use App\Console\CrawlerCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

/**
 * Class Batdongsan
 * @package App\Console\Commands
 */
class Batdongsan extends CrawlerCommand
{
    use Notifiable;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batdongsan {task=fully} {--url=} {--pageFrom=1} {--pageTo}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetching data from Batdongsan.com.vn';

    /**
     * @throws FileNotFoundException
     */
    public function handle()
    {
        parent::handle();
        switch ($this->argument('task')) {
            case 'fully':
                if (!$url = $this->option('url')) {
                    $url = $this->ask('Enter detail URL');
                }

                if (empty($url)) {
                    return;
                }

                $tmpFile = 'batdongsan.tmp';
                $tmpData = 1;

                if (Storage::disk('local')->exists($tmpFile)) {
                    $tmpData = (int) Storage::disk('local')->get($tmpFile);
                }

                // Init with page 1
                if ($tmpData === 0) {
                    $tmpData = 1;
                }

                $items             = app(\App\Services\Crawler\Batdongsan::class)->getIndexLinks(
                    $url,
                    $tmpData,
                    $tmpData + 10
                );
                $this->progressBar = $this->createProgressBar();
                $this->progressBar->setMessage('', 'status');
                $this->progressBar->setMaxSteps($items->count());
                $this->progressBar->setMessage('Pages', 'message');

                $items->each(function ($page) {
                    $this->progressBar->setMessage($page->count(), 'steps');
                    $this->progressBar->setMessage(0, 'step');
                    $this->progressBar->setMessage('', 'status');
                    $page->each(function ($item, $index) {
                        $url = 'https://batdongsan.com.vn'.$item['url'];
                        $this->progressBar->setMessage($item['url'], 'info');
                        /**
                         * @TODO Use Job instead directly
                         */
                        if (!$itemDetail = app(\App\Services\Crawler\Batdongsan::class)->getItemDetail($url)) {
                            $this->progressBar->setMessage($index + 1, 'step');
                            return;
                        }

                        $this->insertItem($itemDetail, $url);
                        $this->progressBar->setMessage($index + 1, 'step');
                    });
                    $this->progressBar->advance();
                });

                Storage::disk('local')->put($tmpFile, $tmpData + 11);

                break;
            case 'add':
                if (!$url = $this->option('url')) {
                    $url = $this->ask('Enter detail URL');
                }

                if (empty($url)) {
                    return;
                }

                if (!$itemDetail = app(\App\Services\Crawler\Batdongsan::class)->getItemDetail($url)) {
                    return;
                }

                $this->insertItem($itemDetail, $url);
                break;
        }
    }

    /**
     * @param $itemDetail
     * @param  string  $url
     */
    private function insertItem($itemDetail, string $url)
    {
        $model = app(\App\Batdongsan::class);
        if ($model->where(['reference_url' => $url])->first()) {
            return;
        }

        $model->insert(array_merge(get_object_vars($itemDetail), ['reference_url' => $url]));
    }
}
