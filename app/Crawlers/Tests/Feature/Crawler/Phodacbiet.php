<?php

namespace App\Crawlers\Tests\Feature\Crawler;

use App\Crawlers\Tests\TestCase;
use App\Crawlers\Tests\Traits\HasCrawler;

/**
 * Class Phodacbiet
 * @package App\Crawlers\Tests\Feature\Crawler
 */
class Phodacbiet extends TestCase
{
    use HasCrawler;

    protected array $itemDetails = [
        'https://phodacbiet.net/threads/mau-viet-voi-em-xong-roi-anh-man-gi-em-cua-tran-viet-anh.3090/' => [
            'url',
            'images',
        ]
    ];

    protected array $itemLinks = [
        'https://phodacbiet.net/forums/anh-hotgirl-nguoi-mau.43/' => [
            'url',
            'title'
        ]
    ];

    protected array $indexLinks = [
        'https://phodacbiet.net/forums/anh-hotgirl-nguoi-mau.43/' => [
            'pageCount' => 20,
            'itemPerPage' => 5
        ]
    ];

    protected string $crawlerClass = \App\Crawlers\Crawler\Phodacbiet::class;
}
