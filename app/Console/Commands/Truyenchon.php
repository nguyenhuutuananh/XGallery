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
use App\Jobs\Truyenchon\Chapters;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class Truyenchon
 * @package App\Console\Commands
 */
class Truyenchon extends BaseCrawlerCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'truyenchon {task=fully} {--url=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetching data from http://truyenchon.com/';

    /**
     * @return bool
     * @throws Exception
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
            /**
             * @var Collection $page
             */
            if ($page->isEmpty()) {
                $this->progressBar->setMessage('', 'steps');
                $this->progressBar->setMessage(0, 'step');
                $this->progressBar->advance();
                return;
            }
            $this->progressBar->setMessage($page->count(), 'steps');
            $this->progressBar->setMessage(0, 'step');
            // Process items on page
            $page->each(function ($item, $index) {
                $this->progressBar->setMessage($item['url'], 'info');
                $this->progressBar->setMessage('FETCHING', 'status');
                // Save a book with information only
                $entityItem = $this->insertItem($item);
                if ($chapters = $this->crawler->getItemChapters($item['url'])) {
                    $entityItem->drop('totalChapters');
                    $entityItem->save();
                    foreach ($chapters as $chapterUrl) {
                        Chapters::dispatch($item, $chapterUrl);
                    }
                }
                $this->progressBar->setMessage($index + 1, 'step');
                $this->progressBar->setMessage('COMPLETED', 'status');
            });
            $this->progressBar->advance();
        });

        return true;
    }

    /**
     * @return bool
     */
    protected function item(): bool
    {
        if (!$url = $this->getOptionUrl()) {
            return false;
        }

        if (!$itemDetail = $this->getCrawler()->getItemDetail($url)) {
            return false;
        }

        $this->insertItem(get_object_vars($itemDetail));

        return true;
    }

    /**
     * @return Model
     */
    protected function getModel(): Model
    {
        return app(\App\Models\Truyenchon::class);
    }
}
