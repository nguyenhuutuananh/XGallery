<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Jobs\Traits;

trait HasJob
{
    /**
     * The number of seconds the job can run before timing out
     * @var int
     */
    public int     $timeout = 600;

    /**
     * The number of times the job may be attempted
     * @var int
     */
    public int $tries = 5;

    /**
     * The maximum number of exceptions to allow before failing.
     * @var int
     */
    public int $maxExceptions = 3;
}
