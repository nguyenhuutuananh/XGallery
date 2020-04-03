<?php

namespace Tests\Traits;

use App\Services\Crawler\CrawlerInterface;
use Illuminate\Support\Facades\Artisan;

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
        Artisan::call('cache:clear');
    }

    public function tearDown(): void
    {
        Artisan::call('cache:clear');
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
            $this->assertIsNumeric($pageCount);
            $this->assertGreaterThanOrEqual(
                $detail['pageCount'],
                $pageCount,
                __('Invalid pageCount on URL '.$indexLink)
            );
        }
    }

//    public function testGetIndexLinks()
//    {
//        foreach ($this->indexLinks as $indexLink => $detail) {
//            $links = $this->crawler->getIndexLinks($indexLink);
//            $this->assertEquals($detail['pageCount'], count($links), 'Invalid pageCount on URL: '. $indexLink);
//        }
//    }

    public function testGetIndexLinksWithFromTo()
    {
        foreach ($this->indexLinks as $indexLink => $detail) {
            $links = $this->crawler->getIndexLinks($indexLink, 1, 4);
            $this->assertEquals(4, $links->count(), __('Invalid pageCount on URL '.$indexLink));
            $links = $links->first();
            $this->assertGreaterThanOrEqual(
                $detail['itemPerPage'],
                $links->count(),
                __('Invalid itemPerPage on URL '.$indexLink)
            );
        }
    }
}
