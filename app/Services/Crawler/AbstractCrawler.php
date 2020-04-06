<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Services\Crawler;

use App\Services\HttpClient;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;
use Spatie\Url\Url;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use function GuzzleHttp\Psr7\build_query;

/**
 * Class AbstractCrawler
 * @package App\Services\Crawler
 * @TODO Do not extend from HttpClient
 */
abstract class AbstractCrawler extends HttpClient implements CrawlerInterface
{
    protected Crawler        $crawler;
    protected array $config;
    protected string $name;

    /**
     * AbstractCrawler constructor.
     * @param  array  $config
     */
    public function __construct(array $config = [])
    {
        $this->config = config('crawler.'.$this->name);

        parent::__construct(
            array_merge(
                $config,
                config('httpclient'),
                isset($this->config['http_client']) ? $this->config['http_client'] : []
            )
        );
    }

    /**
     * @param  string  $uri
     * @return Crawler
     */
    public function crawl(string $uri): ?Crawler
    {
        if (!$response = $this->request(Request::METHOD_GET, $uri)) {
            $this->getLogger()->warning('Can not crawl ' . $uri, $this->getErrors());
            return null;
        }

        $this->crawler = new Crawler($response, $uri);
        return $this->crawler;
    }

    /**
     * @param  string|null  $indexUri
     * @param  int  $from
     * @param  int|null  $to
     * @return Collection
     */
    public function getIndexLinks(
        string $indexUri = null,
        int $from = 1,
        ?int $to = null
    ): Collection {
        $pages = $this->getIndexPagesCount($indexUri);
        $url   = Url::fromString($indexUri);

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
     * @return LoggerInterface
     */
    protected function getLogger(): LoggerInterface
    {
        return Log::stack(['crawl']);
    }
}
