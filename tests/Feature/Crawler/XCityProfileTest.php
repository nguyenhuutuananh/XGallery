<?php

namespace Tests\Feature\Crawler;

use App\Services\Crawler\XCityProfile;
use Tests\TestCase;
use Tests\Traits\HasCrawler;

/**
 * Class XCityProfileTest
 * @package Tests\Feature\Crawler
 */
class XCityProfileTest extends TestCase
{
    use HasCrawler;

    protected array $itemDetails = [
        'https://xxx.xcity.jp/idol/detail/5417/' => [
            'url',
            'cover',
            'name',
            'favorite',
            'birthday',
            'blood_type',
            'city',
            'height',
            'breast',
            'waist',
            'hips'
        ]
    ];

    protected array $itemLinks = [
        'https://xxx.xcity.jp/idol/?kana=%E3%81%82&num=30' => [
            'url',
            'name',
            'cover'
        ]
    ];

    protected array $indexLinks = [
        'https://xxx.xcity.jp/idol/?kana=%E3%81%82&num=30' => [
            'pageCount' => 98,
            'itemPerPage' => 30
        ]
    ];

    protected string $crawlerClass = XCityProfile::class;

    public function testGetItemDetail()
    {
        foreach ($this->itemDetails as $url => $requiredProperties) {
            $item = $this->crawler->getItemDetail($url);

            $this->assertIsObject($item, 'Item detail is not object');
            foreach ($requiredProperties as $requiredProperty) {
                $this->assertObjectHasAttribute(
                    $requiredProperty,
                    $item,
                    __('Attribute '.ucfirst($requiredProperty).' not found')
                );
            }
        }
    }
}
