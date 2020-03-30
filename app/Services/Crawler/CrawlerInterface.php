<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Services\Crawler;

use Illuminate\Support\Collection;

/**
 * Class CrawlerInterface
 * @package App\Services\Crawler
 */
interface CrawlerInterface
{
    public function getItemDetail(string $itemUri): ?object;

    /**
     * Get item links on index page
     * @param  string|null  $indexUri
     * @return Collection
     */
    public function getItemLinks(string $indexUri = null): ?Collection;

    /**
     * Get item links on all index pages
     * @param  string|null  $indexUri
     * @param  int  $from
     * @param  int|null  $to
     * @return Collection
     */
    public function getIndexLinks(string $indexUri = null, int $from = 1, ?int $to = null): Collection;

    public function getIndexPagesCount(string $indexUri): int;

    public function search(array $conditions): ?Collection;

    public function buildUrl(string $path = '', array $parameters = []): string;

    public function download(string $url, string $saveToFile);
}
