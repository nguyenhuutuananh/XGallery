<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Console\Commands;

use App\Console\AbstractCommand;
use App\Console\CrawlerCommand;
use App\Console\Traits\HasCrawler;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use MongoDB\BSON\UTCDateTime;

/**
 * Class Onejav
 * @package App\Console\Commands
 */
class Onejav extends AbstractCommand
{
    use Notifiable;
    use HasCrawler;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'onejav {task=daily} {--url=} {--pageFrom=1} {--pageTo}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetching data from Onejav';

    /**
     * @return bool
     */
    protected function daily(): bool
    {
        $url = 'https://onejav.com/'.date('Y/m/d');
        if (!$pages = $this->getCrawler()->getIndexLinks($url)) {
            return false;
        }

        $this->progressBar = $this->createProgressBar();
        $this->progressBar->setMaxSteps($pages->count());

        $pages->each(function ($items) {
            // Pages process
            $this->progressBar->setMessage(0, 'step');
            $this->progressBar->setMessage($items->count(), 'steps');
            $this->itemsProcess($items);
            $this->progressBar->advance();
        });
        return true;
    }

    /**
     * Process specific index
     * @return bool
     */
    protected function index(): bool
    {
        if (!$url = $this->getOptionUrl()) {
            return false;
        }

        if (!$pages = $this->getCrawler()->getIndexLinks($url)) {
            return false;
        }

        $this->progressBar = $this->createProgressBar();
        $this->progressBar->setMaxSteps($pages->count());

        $pages->each(function ($items) {
            // Pages process
            $this->progressBar->setMessage(0, 'step');
            $this->progressBar->setMessage($items->count(), 'steps');
            $this->itemsProcess($items);
            $this->progressBar->advance();
        });
        return true;
    }

    protected function fully(): bool
    {
        if (!$results = $this->getCrawler()->getItemLinks('https://onejav.com/new?page='.$this->initData[0])) {
            return false;
        }

        $this->createProgressBar();
        $this->progressBar->setMaxSteps(1);
        $this->progressBar->setMessage($results->count(), 'steps');
        $this->itemsProcess($results);

        return true;
    }

    protected function item()
    {
        // TODO: Implement item() method.
    }

    /**
     * Process a collection of items
     * @param  Collection  $items
     */
    private function itemsProcess(Collection $items)
    {
        if ($items->isEmpty()) {
            return;
        }

        $items->each(function ($item, $index) {
            $this->progressBar->setMessage($item['title'], 'info');

            // Convert to Mongo DateTime
            $originalItem = $item;
            if (isset($item['date']) && null !== $item['date']) {
                $item['date'] = new UTCDateTime($item['date']->getTimestamp() * 1000);
            }

            $this->insertItem($item);

            // Process to OneJAV to JavMovies with: Idols & Genres
            \App\Jobs\OneJav::dispatch($originalItem)->onConnection('database');
            $this->progressBar->setMessage($index + 1, 'step');
        });
    }
}
