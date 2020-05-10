<?php

namespace App\Crawlers\Tests\Traits;

use App\Crawlers\Crawler\CrawlerInterface;

/**
 * Trait HasCrawler
 * @package Tests\Traits
 */
trait HasCrawler
{
    protected CrawlerInterface $crawler;

    public function setUp(): void
    {
        parent::setUp();

        $this->crawler = $this->getCrawler();
    }

    /**
     * @return CrawlerInterface
     */
    protected function getCrawler(): CrawlerInterface
    {
        return app($this->crawlerClass);
    }

    public function testGetItemDetail()
    {
        foreach ($this->itemDetails as $url => $requiredProperties) {
            echo $url;
            $item = $this->crawler->getItemDetail($url);

            $this->assertIsObject($item, 'Item detail is not object');
            $this->assertObjectHasAttribute(
                'url',
                $item,
                __('Attribute URL not found')
            );
            foreach ($requiredProperties as $requiredProperty) {
                $this->assertObjectHasAttribute(
                    $requiredProperty,
                    $item,
                    __('Attribute '.ucfirst($requiredProperty).' not found')
                );
            }
        }
    }

    public function testGetItemLinks()
    {
        foreach ($this->itemLinks as $url => $requiredProperties) {
            echo $url;
            $links = $this->crawler->getItemLinks($url);
            foreach ($links as $link) {
                foreach ($requiredProperties as $requiredProperty) {
                    $this->assertArrayHasKey(
                        $requiredProperty,
                        $link,
                        __('Attribute '.ucfirst($requiredProperty).' not found')
                    );
                }

                $this->assertNotNull($link['url'], __('URL is NULL'));
            }
        }
    }

    public function testGetIndexPagesCount()
    {
        foreach ($this->indexLinks as $indexLink => $detail) {
            $pageCount = $this->crawler->getIndexPagesCount($indexLink);
            echo $indexLink . ' pages: ' . $pageCount;
            $this->assertIsNumeric($pageCount);
            $this->assertGreaterThanOrEqual(
                $detail['pageCount'],
                $pageCount,
                __('Invalid pageCount on URL '.$indexLink)
            );
        }
    }

    /**
     *
     */
    public function testGetIndexLinksWithFromTo()
    {
        $expectEndPage = 4;
        foreach ($this->indexLinks as $indexLink => $detail) {
            $pageCount = $this->crawler->getIndexPagesCount($indexLink);
            if ($pageCount < $expectEndPage) {
                $expectEndPage = $pageCount;
            }
            echo $indexLink . ' page from 1 to ' . $expectEndPage;
            $links = $this->crawler->getIndexLinks($indexLink, 1, $expectEndPage);
            $this->assertEquals($expectEndPage, $links->count(), __('Invalid pageCount on URL '.$indexLink));
            $links = $links->first();
            $this->assertGreaterThanOrEqual(
                $detail['itemPerPage'],
                $links->count(),
                __('Invalid itemPerPage on URL '.$indexLink)
            );
        }
    }
}
