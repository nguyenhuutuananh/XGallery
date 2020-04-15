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
 * Class Batdongsan
 * @package App\Console\Commands
 */
class Batdongsan extends BaseCommand
{
    use HasCrawler;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batdongsan {task=fully} {--url} {--pageFrom=1} {--pageTo}';

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
        if (!$endpoint = $this->getCrawlerEndpoint()) {
            return false;
        }

        if (!$pages = $this->getCrawler()->getIndexLinks(
            $endpoint->url,
            (int) $endpoint->page,
            (int) $endpoint->page
        )) {
            return false;
        }

        $endpoint->page = (int) $endpoint->page + 1;
        $endpoint->save();

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

                $this->insertItem(get_object_vars($itemDetail));
                $this->progressBar->setMessage($index + 1, 'step');
                $this->progressBar->setMessage('COMPLETED', 'status');
            });
            $this->progressBar->advance();
        });

        return true;
    }

    protected function daily(): bool
    {
        // TODO: Implement daily() method.
    }

    protected function index(): bool
    {
        // TODO: Implement index() method.
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
}
