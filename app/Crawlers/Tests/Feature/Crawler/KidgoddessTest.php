<?php

namespace App\Crawlers\Tests\Feature\Crawler;

use App\Crawlers\Crawler\Kissgoddess;
use App\Crawlers\Tests\TestCase;
use App\Crawlers\Tests\Traits\HasCrawler;

/**
 * Class KidgoddessTest
 * @package App\Crawlers\Tests\Feature\Crawler
 */
class KidgoddessTest extends TestCase
{
    use HasCrawler;

    protected array $itemDetails = [
        'https://kissgoddess.com/album/32775.html' => [
            'url',
            'images',
        ]
    ];

    protected array $itemLinks = [
        'https://kissgoddess.com/gallery/' => [
            'url',
            'title',
            'cover'
        ]
    ];

    protected array $indexLinks = [
        'https://kissgoddess.com/gallery/' => [
            'pageCount' => 2,
            'itemPerPage' => 5
        ]
    ];

    protected string $crawlerClass = Kissgoddess::class;
}
