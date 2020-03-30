<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Console;

use App\Services\Crawler\CrawlerInterface;

/**
 * Class CrawlerCommand
 * @package App\Console
 */
class CrawlerCommand extends AbstractCommand
{
    protected CrawlerInterface $crawler;

    /**
     * @param  string  $className
     * @return CrawlerInterface
     */
    protected function getCrawler(string $className): CrawlerInterface
    {
        if (isset($this->crawler)) {
            return $this->crawler;
        }

        $this->crawler = app($className);

        return $this->crawler;
    }
}
