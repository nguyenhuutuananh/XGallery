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

/**
 * Class Kissgoddess
 * @package App\Console\Commands
 */
class Kissgoddess extends BaseCrawlerCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kissgoddess {task=fully} {--url=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetching data from https://kissgoddess.com/gallery/';

    /**
     * @return bool
     * @throws Exception
     */
    public function fully(): bool
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
                /**
                 * @TODO Use Job instead directly
                 */
                if (!$itemDetail = $this->getCrawler()->getItemDetail($item['url'])) {
                    $this->progressBar->setMessage($index + 1, 'step');
                    $this->progressBar->setMessage('FAILED', 'status');
                    return;
                }

                $this->insertItem(array_merge(get_object_vars($itemDetail)));
                $this->progressBar->setMessage($index + 1, 'step');
                $this->progressBar->setMessage('COMPLETED', 'status');
            });
            $this->progressBar->advance();
        });

        return true;
    }

    /**
     * @return Model
     */
    protected function getModel(): Model
    {
        return app(\App\Models\Kissgoddess::class);
    }
}
