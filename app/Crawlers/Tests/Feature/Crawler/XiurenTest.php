<?php

namespace App\Crawlers\Tests\Feature\Crawler;

use App\Crawlers\Crawler\Xiuren;
use App\Crawlers\Tests\TestCase;
use App\Crawlers\Tests\Traits\HasCrawler;

/**
 * Class XiurenTest
 * @package App\Crawlers\Tests\Feature\Crawler
 */
class XiurenTest extends TestCase
{
    use HasCrawler;

    protected array $itemDetails = [
        'http://www.xiuren.org/tuigirl-special-lilisha-double.html' => [
            'url',
            'images',
        ]
    ];

    protected array $itemLinks = [
        'http://www.xiuren.org' => [
            'url',
            'cover'
        ]
    ];

    protected array $indexLinks = [
        'http://www.xiuren.org' => [
            'pageCount' => 20,
            'itemPerPage' => 5
        ]
    ];

    protected string $crawlerClass = Xiuren::class;
}
