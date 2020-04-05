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
use Illuminate\Notifications\Notifiable;

/**
 * R18 only used to get videos. There are no idol information
 * @package App\Console\Commands
 */
class R18 extends BaseCommand
{
    use Notifiable;
    use HasCrawler;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'r18 {task=daily} {--url=} {--pageFrom=1} {--pageTo}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetching data from R18';

    /**
     * @return bool
     */
    public function fully(): bool
    {
        if (!$url = $this->getOptionUrl()) {
            return false;
        }

        if (!$pages = $this->getCrawler()->getIndexLinks($url, $this->initData[0], $this->initData[0])) {
            return false;
        }

        $this->progressBar = $this->createProgressBar();
        $this->progressBar->setMaxSteps($pages->count());

        // Process all pages. Actually one page
        $pages->each(function ($page) {
            $this->progressBar->setMessage($page->count(), 'steps');
            $this->progressBar->setMessage(0, 'step');
            // Process items on page
            $page->each(function ($item, $index) {
                $this->progressBar->setMessage($item['url'], 'info');
                $this->progressBar->setMessage('FETCHING', 'status');

                \App\Jobs\R18::dispatch($item)->onConnection('database');

                $this->progressBar->setMessage($index + 1, 'step');
                $this->progressBar->setMessage('QUEUED', 'status');
            });
            $this->progressBar->advance();
        });

        return true;
    }

    public function item(): bool
    {
        // TODO: Implement item() method.
    }

    public function index(): bool
    {
        // TODO: Implement index() method.
    }

    public function daily(): bool
    {
        // TODO: Implement daily() method.
    }
}
