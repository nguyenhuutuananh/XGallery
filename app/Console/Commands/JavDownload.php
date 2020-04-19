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

/**
 * Class JavDownload
 * @package App\Console\Commands
 */
class JavDownload extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jav:downloads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download JAVs';

    public function handle()
    {
        $downloads = \App\Models\JavDownload::where(['is_downloaded' => null])->get();
        $downloads->each(function ($download) {
            \App\Jobs\JavDownload::dispatch($download)->onConnection('database');
        });
    }
}
