<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\OAuth;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class OauthClient
{
    /**
     * @param  string  $method
     * @param  string  $uri
     * @param  array  $parameters
     * @return string|object
     */
    public function request(string $method, string $uri, array $parameters = [])
    {
        $id = md5(serialize([$method, $uri, $parameters]));
        Log::stack(['oauth'])->info(
            Cache::has($id) ? 'Requesting '.$uri.' with CACHE'
                : 'Requesting '.$uri,
            [$method, $uri, $parameters]
        );

        if (Cache::has($id)) {
            return Cache::get($id);
        }

        $client   = $this->getClient();
        $response = $client->request($method, $uri, $parameters);

        if ($response->getStatusCode() !== 200) {
            return null;
        }

        /**
         * @TODO Support decode content via event
         */
        $header  = $response->getHeader('Content-Type')[0] ?? '';
        $content = (string) $response->getBody();

        if (strpos($header, 'application/json') === false) {
            return $content;
        }

        $content = json_decode($content);

        Cache::put($id, $content, 3600);
        return Cache::get($id);
    }

    protected function getClient(): Client
    {
        $stack      = HandlerStack::create();
        $middleware = new Oauth1([
            'consumer_key' => env('FLICKR_KEY'),
            'consumer_secret' => env('FLICKR_SECRET'),
            'token' => env('FLICKR_ACCESS_TOKEN'),
            'token_secret' => env('FLICKR_ACCESS_TOKEN_SECRET'),
        ]);
        $stack->push($middleware);

        return new Client(['handler' => $stack, 'auth' => 'oauth']);
    }
}
