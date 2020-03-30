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
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use MongoDB\BSON\UTCDateTime;

/**
 * Class Onejav
 * @package App\Console\Commands
 */
class Onejav extends CrawlerCommand
{
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
     * Execute the console command.
     *
     * @return mixed
     * @throws FileNotFoundException
     */
    public function handle()
    {
        parent::handle();
        switch ($this->argument('task')) {
            case 'daily':
                $this->process('https://onejav.com/'.date('Y/m/d'));
                break;
            case 'fetch':
                if (!$url = $this->option('url')) {
                    $url = $this->ask('Please enter URL');
                }
                $crawler           = app(\App\Services\Crawler\Onejav::class);
                $results           = $crawler->getItemLinks($url);
                $this->progressBar = $this->createProgressBar();
                $this->progressBar->setMessage('', 'status');
                $this->progressBar->setMaxSteps($results->count());
                $this->itemsProcess($results);
                break;
            case 'fully':
                $tmpFile = 'onejav.tmp';
                $tmpData = [1, 0];

                if (Storage::disk('local')->exists($tmpFile)) {
                    $tmpData    = explode(':', Storage::disk('local')->get($tmpFile));
                    $tmpData[0] = (int) $tmpData[0];
                    $tmpData[1] = isset($tmpData[1]) ? $tmpData[1] : 0;
                }

                // Init with page 1
                if ($tmpData[0] === 0) {
                    $tmpData[0] = 1;
                }

                if ($tmpData[1] === 4) {
                    return;
                }

                $crawler = app(\App\Services\Crawler\Onejav::class);
                $results = $crawler->getItemLinks('https://onejav.com/new?page='.$tmpData[0]);
                $tmpData[0]++;

                // 404 then count ++
                if (!$results) {
                    $tmpData[1]++;
                } else {
                    // Reset count
                    $tmpData[1] = 0;
                }

                if ($results) {
                    $this->progressBar = $this->createProgressBar();
                    $this->progressBar->setMessage('', 'status');
                    $this->progressBar->setMaxSteps($results->count());
                    $this->itemsProcess($results);
                }

                Storage::disk('local')->put($tmpFile, $tmpData[0]++.':'.$tmpData[1]++);
                break;
            case 'guide':
                $this->ask('What do you want to do');
                break;
            default:
                if ($url = $this->option('url')) {
                    $this->process($url);
                }
                break;
        }
    }

    /**
     * Fetch OneJav movies and store into Mongodb
     * @param  string  $url
     * @param  int|null  $from
     * @param  int|null  $to
     */
    private function process(string $url, int $from = 1, int $to = null)
    {
        $crawler = app(\App\Services\Crawler\Onejav::class);
        $results = $crawler->getIndexLinks($url, $from, $to);

        $this->output->writeln('URL '.$url);
        $this->progressBar = $this->createProgressBar();
        $this->progressBar->setMaxSteps($results->count());

        $results->each(function ($items) {
            // Pages process
            $this->progressBar->setMessage('Pages', 'message');
            $this->progressBar->setMessage($items->count(), 'steps');
            $this->progressBar->setMessage(0, 'step');
            $this->progressBar->setMessage('', 'status');
            $this->itemsProcess($items);
            $this->progressBar->advance();
        });
        /**
         * @TODO Show number of items / processed / failed
         */
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

        $items->each(function ($item, $key) {
            $this->progressBar->setMessage($item['title'], 'info');
            // Item process
            $model = app(\App\Onejav::class);

            if ($model->where(['url' => $item['url']])->first()) {
                $this->progressBar->setMessage($key + 1, 'step');
                return;
            }

            // Convert to Mongo DateTime
            $originalItem = $item;
            $item['date'] = new UTCDateTime($item['date']->getTimestamp() * 1000);

            $model->insert($item);

            // Process to OneJAV to JavMovies with: Idols & Genres
            \App\Jobs\OneJav::dispatch($originalItem)->onConnection('database');
            $this->progressBar->setMessage($key + 1, 'step');
        });
    }
}
