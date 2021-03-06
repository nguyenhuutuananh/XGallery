<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Crawlers\Crawler;

use App\Crawlers\HttpClient;
use App\Traits\HasObject;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;
use Spatie\Url\Url;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use function GuzzleHttp\Psr7\build_query;

/**
 * Class AbstractCrawler
 * @package App\Crawlers\Crawler
 */
abstract class AbstractCrawler implements CrawlerInterface
{
    use HasObject;

    protected Crawler        $crawler;
    protected array          $config;
    protected string         $name;

    /**
     * AbstractCrawler constructor.
     * @param  array  $config
     */
    public function __construct(array $config = [])
    {
        $this->config = array_merge($config, config('crawler.'.strtolower($this->getShortClassname())));
        $this->config['cache'] = $this->config['cache'] ?? 3600;
    }

    /**
     * @param  string  $uri
     * @return Crawler
     */
    public function crawl(string $uri): ?Crawler
    {
        if (!$response = $this->getClient()->request(Request::METHOD_GET, $uri)) {
            $this->getLogger()->warning('Can not crawl '.$uri);
            return null;
        }

        $this->crawler = new Crawler($response, $uri);
        return $this->crawler;
    }

    /**
     * @param  array  $options
     * @return HttpClient
     */
    public function getClient(array $options = []): HttpClient
    {
        $options = array_merge(
            $options,
            config('httpclient'),
            isset($this->config['http_client']) ? $this->config['http_client'] : []
        );

        return new HttpClient($options);
    }

    /**
     * @return LoggerInterface
     */
    protected function getLogger(): LoggerInterface
    {
        return Log::stack(['crawl']);
    }

    /**
     * @param  string|null  $indexUri
     * @param  int  $from
     * @param  int|null  $to
     * @return Collection
     */
    public function getIndexLinks(string $indexUri = null, int $from = 1, ?int $to = null): Collection
    {
        $pages = $this->getIndexPagesCount($indexUri);
        $url = Url::fromString($indexUri);

        $links = collect();

        for ($page = $from; $page <= $pages; $page++) {
            $indexUrl = $this->buildUrlWithPage($url, $page);

            /**
             * @TODO Page 1 is crawled no need for re-crawl
             */
            $links->put($indexUrl, $this->getItemLinks($indexUrl));
            if (null !== $to && $page == $to) {
                break;
            }
        }

        return $links;
    }

    /**
     * @param  Url  $url
     * @param  int  $page
     * @return string
     */
    protected function buildUrlWithPage(Url $url, int $page): string
    {
        return $this->buildUrl(
            $url->getPath(),
            array_merge($url->getAllQueryParameters(), ['page' => $page]),
            false
        );
    }

    /**
     * @param  string  $path
     * @param  array  $parameters
     * @param  int  $encoding
     * @return string
     */
    public function buildUrl(string $path = '', array $parameters = [], $encoding = PHP_QUERY_RFC3986): string
    {
        $query = empty($parameters) ? '' : '?'.build_query($parameters, $encoding);
        return Url::fromString($this->config['http_client']['base_uri'])->withPath($path).$query;
    }

    /**
     * @param  string  $url
     * @param  string  $saveToFile
     * @return bool|string
     */
    public function download(string $url, string $saveToFile)
    {
        return $this->getClient()->download($url, $saveToFile);
    }
}
