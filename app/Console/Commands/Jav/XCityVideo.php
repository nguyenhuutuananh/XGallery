<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Console\Commands\Jav;

use App\Console\BaseCrawlerCommand;
use Exception;

/**
 * Class XCityVideo
 * @package App\Console\Commands
 */
class XCityVideo extends BaseCrawlerCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jav:xcityvideo {task=fully}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetching video data from XCity';

    /**
     * @return bool
     * @throws Exception
     */
    public function fully(): bool
    {
        if (!$endpoint = $this->getCrawlerEndpoint()) {
            return false;
        }

        $items = $this->getCrawler()->getItemLinks($endpoint->url.$endpoint->page);

        if (!$items || $items->isEmpty()) {
            $endpoint->failed = (int) $endpoint->failed + 1;
            if ($endpoint->failed === 10) {
                $endpoint->page = 1;
                $endpoint->failed = 0;
                $endpoint->save();
                return false;
            }

            $endpoint->page = (int) $endpoint->page + 1;
            $endpoint->save();
            return false;
        }

        $endpoint->page = (int) $endpoint->page + 1;
        $endpoint->save();

        $this->createProgressBar();
        $this->progressBar->setMaxSteps(1);
        $this->progressBar->setMessage($items->count(), 'steps');

        $items->each(function ($item, $index) {
            $this->progressBar->setMessage($item['title'], 'info');
            // This queue trigger on limited channel
            \App\Jobs\Jav\XCityVideo::dispatch($item);
            $this->progressBar->setMessage('QUEUED', 'status');
            $this->progressBar->setMessage($index + 1, 'step');
        });

        return true;
    }

    /**
     * @return bool
     */
    public function daily(): bool
    {
        if (!$items = $this->getCrawler()->getItemLinks('https://xxx.xcity.jp/avod/list/?style=simple')) {
            return false;
        }

        $this->progressBar = $this->createProgressBar();
        $this->progressBar->setMaxSteps($items->count());

        $items->each(function ($item) {
            $this->progressBar->setMessage($item['url'], 'info');
            $this->progressBar->setMessage('FETCHING', 'status');
            // Because this is daily request. We don't need use limit channel
            \App\Jobs\Jav\XCityVideo::dispatch($item);
            $this->progressBar->setMessage('QUEUED', 'status');
            $this->progressBar->advance();
        });

        return true;
    }
}
