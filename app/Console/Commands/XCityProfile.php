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
use App\Console\Traits\HasCrawler;
use Illuminate\Notifications\Notifiable;

/**
 * Class XCity
 * @package App\Console\Commands
 */
class XCityProfile extends AbstractCommand
{
    use Notifiable;
    use HasCrawler;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xcity:profile {task=daily} {--url=} {--pageFrom=1} {--pageTo}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetching profile data from XCity';

    protected function daily(): bool
    {
    }

    protected function index(): bool
    {
    }

    /**
     * @return bool
     */
    protected function fully(): bool
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

                \App\Jobs\XCityProfile::dispatch($item)->onConnection('database');

                $this->progressBar->setMessage($index + 1, 'step');
                $this->progressBar->setMessage('QUEUED', 'status');
            });
            $this->progressBar->advance();
        });

        return true;
    }

    protected function item(): bool
    {
    }
}
