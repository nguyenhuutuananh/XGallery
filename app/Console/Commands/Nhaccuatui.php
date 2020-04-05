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
use App\Jobs\DownloadNhacCuaTui;

/**
 * Class Nhaccuatui
 * @package App\Console\Commands
 */
class Nhaccuatui extends BaseCommand
{
    use HasCrawler;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nhaccuatui {task=fetch} {download=0} {--title} {--singer} {--pageFrom=1} {--pageTo}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetching data from Nhaccuatui';

    /**
     * @return bool
     */
    protected function search(): bool
    {
        // We will get all data of result. It would take time
        $pages = $this->getCrawler()->search([
            'title' => $this->option('title'),
            'singer' => $this->option('singer')
        ]);

        if (!$pages) {
            return false;
        }

        $this->progressBar = $this->createProgressBar();
        $this->progressBar->setMaxSteps($pages->count());

        $pages->each(function ($items, $key) {
            $this->progressBar->setMessage($items->count(), 'steps');
            $this->progressBar->setMessage(0, 'step');
            $items->each(function ($item, $key) {
                $this->progressBar->setMessage($item['name'], 'info');
                $this->progressBar->setMessage('', 'status');

                if ($this->argument('download') == 1) {
                    DownloadNhacCuaTui::dispatch($item['url'])->onConnection('database');
                    $this->progressBar->setMessage('Added to download queues', 'status');
                }

                // Item process
                $this->insertItem($item);
                $this->progressBar->setMessage($key + 1, 'step');
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

    protected function fully(): bool
    {
        // TODO: Implement fully() method.
    }

    protected function index()
    {
        // TODO: Implement index() method.
    }

    protected function item()
    {
        // TODO: Implement item() method.
    }
}
