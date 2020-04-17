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
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use MongoDB\BSON\UTCDateTime;

/**
 * Class Onejav
 * @package App\Console\Commands
 */
class Onejav extends BaseCrawlerCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'onejav {task=daily} {--url=}';

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
        return $this->indexProcess('https://onejav.com/'.date('Y/m/d'));
    }

    /**
     * Get links in a index page and process these
     * @param  string  $url
     * @return bool
     */
    private function indexProcess(string $url)
    {
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

    /**
     * Process to get all OneJav data
     * @return bool
     * @throws Exception
     */
    protected function fully(): bool
    {
        if (!$endpoint = $this->getCrawlerEndpoint()) {
            return false;
        }

        // For moment we can't use getIndexLinks because we are using recursive to get last page of this site
        if (!$results = $this->getCrawler()->getItemLinks($endpoint->url.$endpoint->page)) {
            return false;
        }

        $endpoint->page = (int) $endpoint->page + 1;
        $endpoint->save();

        $this->createProgressBar();
        $this->progressBar->setMaxSteps(1);
        $this->progressBar->setMessage($results->count(), 'steps');
        $this->itemsProcess($results);

        return true;
    }

    /**
     * @return Model
     */
    protected function getModel(): Model
    {
        return app(\App\Models\Onejav::class);
    }
}
