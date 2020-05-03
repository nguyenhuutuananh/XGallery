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
            $page->each(function ($story, $index) {
                $this->progressBar->setMessage($story['url'], 'info');
                $this->progressBar->setMessage('FETCHING', 'status');
                // Save a book with information only
                $this->insertItem($story);
                /**
                 * Update chapters for each book
                 * @TODO Reduce update if chapters already here
                 */
                if ($chapters = $this->crawler->getItemChapters($story['url'])) {
                    foreach ($chapters as $chapterUrl) {
                        Chapters::dispatch($story, $chapterUrl)->onQueue('truyenchon');
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
     * Update chapters for a story
     * @return bool
     */
    protected function story(): bool
    {
        if (!$url = $this->getOptionUrl()) {
            return false;
        }

        if (!$chapters = $this->getCrawler()->getItemChapters($url)) {
            return false;
        }

        if (!$entity = \App\Models\Truyenchon::where(['url' => $url])->first()) {
            return false;
        }

        foreach ($chapters as $chapterUrl) {
            Chapters::dispatch(['url' => $url], $chapterUrl)->onQueue('truyenchon');
        }

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
