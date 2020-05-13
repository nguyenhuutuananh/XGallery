<?php


namespace App\Jobs\Middleware;

use Illuminate\Contracts\Redis\LimiterTimeoutException;
use Illuminate\Support\Facades\Redis;

/**
 * Class FlickrRateLimited
 * @package App\Jobs\Middleware
 */
class FlickrRateLimited
{
    private string $key;
    private int    $allow;
    private int    $every;
    private int    $block;

    public function __construct(string $key = 'flickr', int $allow = 2, int $every = 1, int $block = 2)
    {
        $this->key = $key;
        $this->allow = $allow;
        $this->every = $every;
        $this->block = $block;
    }

    /**
     * @param $job
     * @param $next
     * @throws LimiterTimeoutException
     */
    public function handle($job, $next)
    {
        Redis::throttle($this->key)
            ->allow($this->allow)->every($this->every)->block($this->every)
            ->then(function () use ($job, $next) {
                $next($job);
            }, function () use ($job) {
                $job->release($this->block * 1.5);
            });
    }
}
