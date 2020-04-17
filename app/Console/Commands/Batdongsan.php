<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Console\Commands;

use App\Console\BaseCrawlerCommand;

/**
 * Class Batdongsan
 * @package App\Console\Commands
 */
class Batdongsan extends BaseCrawlerCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batdongsan {task=fully}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetching data from Batdongsan.com.vn';

    /**
     * @return bool
     */
    protected function fully(): bool
    {
        if (!$pages = $this->getIndexLinks()) {
            return false;
        }

        $this->progressBar = $this->createProgressBar();
        $this->progressBar->setMaxSteps($pages->count());

        // Process all pages
        $pages->each(function ($page) {
            $this->progressBar->setMessage($page->count(), 'steps');
            $this->progressBar->setMessage(0, 'step');
            // Process items on page
            $page->each(function ($item, $index) {
                $this->progressBar->setMessage($item['url'], 'info');
                $this->progressBar->setMessage('FETCHING', 'status');
                \App\Jobs\Batdongsan::dispatch($item['url'])->onConnection('database');
                $this->progressBar->setMessage($index + 1, 'step');
                $this->progressBar->setMessage('QUEUED', 'status');
            });
            $this->progressBar->advance();
        });

        return true;
    }
}
