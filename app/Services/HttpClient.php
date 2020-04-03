<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Services;

use App\Events\OnHttpRequested;
use App\Services\Crawler\Traits\HasCurl;
use Campo\UserAgent;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HttpClient
 * @package App\Services
 */
class HttpClient extends Client
{
    use HasCurl;

    protected ResponseInterface $response;

    /**
     * HttpClient constructor.
     * @param  array  $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct(array_merge($config, config('httpclient')));
    }

    /**
     * @param  string  $method
     * @param  string  $uri
     * @param  array  $options
     * @return string|null
     */
    public function request($method, $uri = '', array $options = []): ?string
    {
        $key = $this->getKey([$method, $uri]);
        Log::stack(['http'])
            ->info(
                Cache::has($key)
                    ? 'Request URI: '.urldecode($uri).' with CACHED key '.$key
                    : 'Request URI: '.urldecode($uri)
            );

        if (Cache::has($key)) {
            return Cache::get($key);
        }

        try {
            $this->response = parent::request(
                $method,
                $uri,
                array_merge(
                    $options,
                    [
                        'headers' => [
                            'Accept-Encoding' => 'gzip',
                            'User-Agent' => UserAgent::random([
                                'device_type' => ['Desktop'],
                            ]),
                        ],
                    ],
                )
            );
            event(new OnHttpRequested($this->response));
        } catch (Exception $exception) {
            Log::stack(['http'])->error($exception->getMessage());
            return null;
        }

        switch ($this->response->getStatusCode()) {
            case Response::HTTP_OK:
                Cache::put($key, $this->response->getBody()->getContents());
                break;
        }

        return Cache::get($key);
    }

    /**
     * @param  array  $args
     * @return string
     */
    protected function getKey(array $args): string
    {
        return md5(serialize($args));
    }

    /**
     * @param  string  $url
     * @param  string  $saveTo
     * @return bool|string
     */
    public function download(string $url, string $saveTo)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            return false;
        }

        if (!Storage::exists($saveTo)) {
            Storage::makeDirectory($saveTo);
        }

        $fileName   = basename($url);
        $saveToFile = $saveTo.DIRECTORY_SEPARATOR.$fileName;

        if (file_exists($saveToFile)) {
            /**
             * @TODO Verify local file
             */
            return $saveToFile;
        }

        return $this->downloadRemoteFile($url, $saveToFile);
    }
}
