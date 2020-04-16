<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Console\Traits;

trait HasCommand
{
    /**
     * @return string|null
     */
    protected function getOptionUrl(): ?string
    {
        if (!$url = $this->option('url')) {
            $url = $this->ask('Please enter URL');
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }

        return $url;
    }
}
