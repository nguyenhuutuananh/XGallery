<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Crawlers\Traits;

use Campo\UserAgent;
use Exception;

/**
 * Trait HasHeaders
 * @package App\Crawlers\Traits
 */
trait HasHeaders
{
    /**
     * @return array
     * @throws Exception
     */
    protected function getHeaders(): array
    {
        return [
            'Accept-Encoding' => 'gzip, deflate',
            'User-Agent' => UserAgent::random([
                'device_type' => ['Desktop'],
            ]),
        ];
    }
}
