<?php

namespace App\Crawlers\Tests\Feature\Crawler;

use App\Crawlers\Crawler\Onejav;
use App\Crawlers\Tests\TestCase;
use App\Crawlers\Tests\Traits\HasCrawler;

/**
 * Class OnejavTest
 * @package Tests\Feature\Crawler
 */
class OnejavTest extends TestCase
{
    use HasCrawler;

    /**
     * @var array|string[]
     */
    protected array $itemDetails = [
        'https://onejav.com/torrent/mide705' => [
            'url',
            'cover',
            'title',
            'size',
            'date',
            'tags',
            'description',
            'actresses',
            'torrent'
        ]
    ];

    protected array $itemLinks = [
        'https://onejav.com/2020/03/08' => [
            'url',
            'cover',
            'title',
            'size',
            'date',
            'tags',
            'description',
            'actresses',
            'torrent'
        ]
    ];

    protected array $indexLinks = [
        'https://onejav.com/2020/03/08' => [
            'pageCount' => 31,
            'itemPerPage' => 10
        ]
    ];

    protected string $crawlerClass = Onejav::class;
}
