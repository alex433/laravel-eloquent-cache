<?php

namespace Alex433\LaravelEloquentCache;

use Illuminate\Support\Facades\Cache;


trait Cachable
{
    //public $cacheTtl = null;

    //public $cacheStore = null;

    //public $cacheTags;

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
     * @return \App\Traits\Models\Builder|static
     */
    public function newEloquentBuilder($query)
    {
        return new Builder($query);
    }

    public function forget()
    {
        $this->getCache()
            ->forget($this->getCacheKey($this->{$this->primaryKey}));

        return $this;
    }

    public function getCache()
    {
        $cache = Cache::store($this->cacheStore);

        if ($this->cacheTags) {
            $cache = $cache->tags($this->cacheTags);
        }

        return $cache;
    }

    public function getCacheKey($identifier)
    {
        return $this->getTable() . '.' . $identifier;
    }
}
