<?php namespace Krisawzm\DemoManager\Classes;

use October\Rain\Support\Traits\Singleton;
use Cache;
use Config;
use File;

class UserCounter
{
    use Singleton;

    /**
     * Cache key.
     *
     * @var string
     */
    protected $cacheKey = 'krisawzm-demomanager-usercounter';

    /**
     * Initialize.
     *
     * @return void
     */
    public function init()
    {
        if (!Cache::has($this->cacheKey)) {
            Cache::forever(
                $this->cacheKey,
                count(File::directories(themes_path())) - 1
            );
        }
    }

    /**
     * Get the number of demo users.
     *
     * @return int
     */
    public function get()
    {
        return Cache::get($this->cacheKey);
    }

    /**
     * Increment the counter.
     *
     * @return int
     */
    public function inc()
    {
        return Cache::forever($this->cacheKey, self::get() + 1);
    }

    /**
     * Returns true if the limit is reached.
     *
     * @return bool
     */
    public function limit()
    {
        return self::get() >= Config::get('krisawzm.demomanager::limit', 500);
    }
}
