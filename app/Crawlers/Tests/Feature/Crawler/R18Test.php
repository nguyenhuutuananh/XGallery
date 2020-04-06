<?php

namespace App\Crawlers\Tests\Feature\Crawler;

use App\Crawlers\Crawler\R18;
use App\Crawlers\Tests\TestCase;
use App\Crawlers\Tests\Traits\HasCrawler;

/**
 * Class R18Test
 * @package Tests\Feature\Crawler
 */
class R18Test extends TestCase
{
    use HasCrawler;

    protected array $itemDetails = [
        'https://www.r18.com/videos/vod/movies/detail/-/id=ssni00703/' => [
            'url',
            'cover',
            'name',
            'categories',
            'actress',
            'sample',
            'gallery',
        ]
    ];

    protected array $itemLinks = [
        'https://www.r18.com/videos/vod/movies/list/pagesize=120/price=all/sort=new/type=all' => [
            'url',
            'cover'
        ]
    ];

    protected array $indexLinks = [
        'https://www.r18.com/videos/vod/movies/list/pagesize=120/price=all/sort=new/type=all' => [
            'pageCount' => 417,
            'itemPerPage' => 120
        ]
    ];

    protected string $crawlerClass = R18::class;
}
