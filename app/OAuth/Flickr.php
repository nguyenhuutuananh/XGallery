<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\OAuth;

class Flickr extends OauthClient
{
    const REST_ENDPOINT = 'https://api.flickr.com/services/rest';

    /**
     * @param  string  $method
     * @param  array  $parameters
     * @return object|null
     */
    public function get(string $method, array $parameters = []): ?object
    {
        $content = $this->request(
            'GET',
            static::REST_ENDPOINT,
            [
                'query' => array_merge(
                    ['method' => 'flickr.'.$method],
                    $this->getDefaultFlickrParameters(),
                    $parameters
                )
            ]
        );

        if ($content->stat !== 'ok') {
            return null;
        }

        return $content;
    }

    /**
     * Default parameters for all requests
     *
     * @return array
     */
    private function getDefaultFlickrParameters()
    {
        return ['format' => 'json', 'nojsoncallback' => 1, 'api_key' => env('FLICKR_KEY')];
    }
}
