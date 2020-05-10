<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Crawlers\Crawler;

use Exception;
use Illuminate\Support\Collection;
use stdClass;

/**
 * Class Phodacbiet
 * @package App\Crawlers\Crawler
 */
class Phodacbiet extends AbstractCrawler
{
    public function getItemDetail(string $itemUri): ?object
    {
        $crawler = null === $itemUri ? $this->crawler : $this->crawl($itemUri);

        if (!$crawler) {
            $this->getLogger()->warning('Can not get crawler on URI '.$itemUri);
            return null;
        }

        $item = new stdClass;
        $item->url = $itemUri;
        $item->images = collect($crawler->filter('.bbWrapper img.bbImage')->each(
            function ($el) {
                return $el->attr('src');
            }
        ))->reject(function ($value) {
            return null === $value;
        })->toArray();

        return $item;
    }

    /**
     * @param  string  $indexUri
     * @return int
     */
    public function getIndexPagesCount(string $indexUri): int
    {
        $crawler = null === $indexUri ? $this->crawler : $this->crawl($indexUri);

        try {
            return (int) $crawler->filter('ul.pageNav-main li.pageNav-page ')->last()->text();
        } catch (Exception $exception) {
            return 1;
        }
    }

    public function search(array $conditions): ?Collection
    {
        // TODO: Implement search() method.
    }

    public function getItemLinks(string $indexUri = null): ?Collection
    {
        return $this->getPosts($indexUri);
    }

    /**
     * @param  string  $forumLink
     * @return Collection|null
     */
    protected function getPosts(string $forumLink): ?Collection
    {
        $crawler = null === $forumLink ? $this->crawler : $this->crawl($forumLink);

        if (!$crawler) {
            $this->getLogger()->warning('Can not get crawler on URI '.$forumLink);
            return null;
        }

        try {
            return collect(
                $crawler->filter('.threadList .cate.post.thread a')->each(
                    function ($anchor) {
                        return [
                            'url' => 'https://phodacbiet.info'.$anchor->attr('href'),
                            'title' => $anchor->attr('title'),
                        ];
                    }
                )
            );
        } catch (Exception $exception) {
            return null;
        }
    }
}
