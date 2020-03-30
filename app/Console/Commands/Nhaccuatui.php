<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Console\Commands;

use App\Console\CrawlerCommand;
use App\Jobs\DownloadNhacCuaTui;
use Illuminate\Notifications\Notifiable;

/**
 * Class Nhaccuatui
 * @package App\Console\Commands
 */
class Nhaccuatui extends CrawlerCommand
{
    use Notifiable;

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        switch ($this->argument('task')) {
            case 'fetch':
                $this->progressBar = $this->createProgressBar();
                /**
                 * @TODO Get singleton crawler
                 */
                $results = $this->getCrawler(\App\Services\Crawler\Nhaccuatui::class)->search([
                    'title' => $this->option('title'),
                    'singer' => $this->option('singer')
                ]);

                $this->progressBar->setMaxSteps($results->count());
                $results->each(function ($items, $key) {
                    // Pages process
                    $this->progressBar->setMessage('Pages', 'message');
                    $this->progressBar->setMessage($items->count(), 'steps');
                    $this->progressBar->setMessage(0, 'step');
                    $this->progressBar->setMessage('', 'status');
                    $items->each(function ($item, $key) {
                        $this->progressBar->setMessage($item['name'], 'info');

                        if ($this->argument('download') == 1) {
                            // Use jobs to download files
                            DownloadNhacCuaTui::dispatch($item['url'])->onConnection('database');

                            $this->progressBar->setMessage('Downloaded', 'status');
                        }

                        // Item process
                        $model = app(\App\Nhaccuatui::class);
                        if ($model->where(['url' => $item['url']])->first()) {
                            $this->progressBar->setMessage($key + 1, 'step');
                            return;
                        }

                        $model->insert($item);
                        $this->progressBar->setMessage($key + 1, 'step');
                    });

                    $this->progressBar->advance();
                });
                break;
            case 'guide':
            default:
                $this->ask('What do you want to do');
                break;
        }
    }
}
