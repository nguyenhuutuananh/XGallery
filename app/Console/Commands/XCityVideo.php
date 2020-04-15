<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Console\Commands;

use App\Console\BaseCommand;
use App\Console\Traits\HasCrawler;

/**
 * Class XCityVideo
 * @package App\Console\Commands
 */
class XCityVideo extends BaseCommand
{
    use HasCrawler;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xcity:video {task=fully} {--url} {--pageFrom=1} {--pageTo}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetching video data from XCity';

    /**
     * @return bool
     */
    public function fully(): bool
    {
        if (!$endpoint = $this->getCrawlerEndpoint()) {
            return false;
        }

        if (!$movies = $this->getCrawler()->getItemLinks($endpoint->url.$endpoint->page)) {
            return false;
        }

        $endpoint->page = (int) $endpoint->page + 1;
        $endpoint->save();

        $this->createProgressBar();
        $this->progressBar->setMaxSteps(1);
        $this->progressBar->setMessage($movies->count(), 'steps');

        $movies->each(function ($item, $index) {
            $this->progressBar->setMessage($item['title'], 'info');
            \App\Jobs\XCityVideo::dispatch($item)->onConnection('database');
            $this->progressBar->setMessage($index + 1, 'step');
        });

        return true;
    }

    public function item(): bool
    {
        // TODO: Implement item() method.
        return true;
    }

    public function daily(): bool
    {
        // TODO: Implement daily() method.

        return true;
    }

    public function index(): bool
    {
        // TODO: Implement index() method.
        return true;
    }
}
