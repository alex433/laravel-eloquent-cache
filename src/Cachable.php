<?php

namespace Alex433\LaravelEloquentCache;

use Illuminate\Support\Facades\Cache;


trait Cachable
{
    /**
     * Cache TTL in seconds. Defaults indefinitely
     *
     * @var int $cacheTtl
     */

    /**
     * Cache store name. Defaults default cache connection
     *
     * @var string $cacheStore
     */

    /**
     * Cache tags. Defaults no tags
     *
     * @var array $cacheTags
     */

    /**
     * Boot trait for a model.
     *
     * @return void
     */
    public static function bootCachable()
    {
        static::created(function ($model) {
            $model->forget();
        });

        static::updated(function ($model) {
            $model->forget();
        });

        static::deleted(function ($model) {
            $model->forget();
        });
    }

    /**
     * Create a new Eloquent cache query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @return \Alex433\LaravelEloquentCache\Builder|static
     */
    public function newEloquentBuilder($query)
    {
        return new Builder($query);
    }

    /**
     * Remove an item from the cache.
     *
     * @return $this
     */
    public function forget()
    {
        $this->getCache()
            ->forget($this->getCacheKey($this->{$this->primaryKey}));

        return $this;
    }

    /**
     * Get cache instance.
     *
     * @return \Illuminate\Support\Facades\Cache
     */
    public function getCache()
    {
        $cache = Cache::store($this->cacheStore);

        if ($this->cacheTags) {
            $cache = $cache->tags($this->cacheTags);
        }

        return $cache;
    }

    /**
     * Get cache key for the model.
     *
     * @param  int|string $identifier
     * @return string
     */
    public function getCacheKey($identifier)
    {
        return $this->getTable() . '.' . $identifier;
    }
}
