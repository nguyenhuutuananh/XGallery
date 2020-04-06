<?php

namespace App\Crawlers\Tests\Feature\Crawler;

use App\Crawlers\Crawler\XCityVideo;
use App\Crawlers\Tests\TestCase;
use App\Crawlers\Tests\Traits\HasCrawler;

/**
 * Class XCityVideoTest
 * @package Tests\Feature\Crawler
 */
class XCityVideoTest extends TestCase
{
    use HasCrawler;

    protected array $itemDetails = [
        'https://xxx.xcity.jp/avod/detail/?id=142426' => [
            'url',
            'title',
            'gallery'
        ]
    ];

    protected array $itemLinks = [
        'https://xxx.xcity.jp/avod/list/?style=simple' => [
            'url',
            'title',
            'cover'
        ]
    ];

    protected array $indexLinks = [
        'https://xxx.xcity.jp/avod/list/?style=simple' => [
            'pageCount' => 98,
            'itemPerPage' => 30
        ]
    ];

    protected string $crawlerClass = XCityVideo::class;
}
