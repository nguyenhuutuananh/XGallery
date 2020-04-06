<?php

namespace App\Crawlers\Tests\Feature\Crawler;

use App\Crawlers\Crawler\Batdongsan;
use App\Crawlers\Tests\TestCase;
use App\Crawlers\Tests\Traits\HasCrawler;

/**
 * Class BatdongsanTest
 * @package Tests\Feature\Crawler
 */
class BatdongsanTest extends TestCase
{
    use HasCrawler;

    protected array $itemDetails = [
        'https://batdongsan.com.vn/ban-can-ho-chung-cu-duong-dien-bien-phu-phuong-22-prj-vinhomes-central-park/sang-nhuong-gia-tot-1pn-chi-2-5ty-2pn-chi-tu-3-5-ty-3pn-lo-0903163021-pr13660562' => [
            'name',
            'price',
            'size',
            'content',
            'url'
        ]
    ];

    protected array $itemLinks = [
        'https://batdongsan.com.vn/ban-can-ho-chung-cu' => [
            'url',
            'title',
            'cover'
        ]
    ];

    protected array $indexLinks = [
        'https://batdongsan.com.vn/ban-can-ho-chung-cu' => [
            'pageCount' => 1000,
            'itemPerPage' => 20
        ]
    ];

    protected string $crawlerClass = Batdongsan::class;
}
