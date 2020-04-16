<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Console;

use App\Console\Traits\Crawlers\HasCrawler;
use App\Console\Traits\HasCommand;
use App\Traits\HasObject;

/**
 * Class BaseCrawlerCommand
 * @package App\Console
 */
class BaseCrawlerCommand extends BaseCommand
{
    use HasObject;
    use HasCommand;
    use HasCrawler;

    /**
     * Process WHOLE site by specific URL
     * @return bool
     */
    protected function fully(): bool
    {
        return false;
    }

    /**
     * Process specific daily index page
     * @return bool
     */
    protected function daily(): bool
    {
        return false;
    }

    protected function index(): bool
    {
        return false;
    }

    protected function item(): bool
    {
        return false;
    }
}
