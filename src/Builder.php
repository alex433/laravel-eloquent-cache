<?php

namespace Alex433\LaravelEloquentCache;

use Illuminate\Database\Eloquent\Builder as BaseBuilder;

class Builder extends BaseBuilder
{
    /**
     * Get the hydrated models from cache.
     *
     * @param  array $columns
     * @return \Illuminate\Database\Eloquent\Model[]|static[]
     */
    public function getModels($columns = ['*'])
    {
        if ($this->isKeyQuery()) {
            return $this->model->hydrate(
                $this->model->getCache()
                    ->remember(
                        $this->model->getCacheKey($this->query->wheres[0]['value']),
                        $this->model->cacheTtl,
                        function () use ($columns) {
                            return $this->query->get($columns)->all();
                        })
            )->all();
        }

        return parent::getModels($columns);
    }

    /**
     * Check whether the request is only by primary key.
     *
     * @return bool
     */
    protected function isKeyQuery()
    {
        return count($this->query->wheres) == 1
            && $this->query->wheres[0]['type'] == 'Basic'
            && $this->query->wheres[0]['operator'] == '='
            && in_array(
                $this->query->wheres[0]['column'],
                [$this->model->getKeyName(), $this->model->getQualifiedKeyName()]
            );
    }

    /**
     * Remove all model items from the cache.
     *
     * @return bool
     */
    public function flushCache()
    {
        if (!$this->model->cacheTags) {
            return false;
        }

        return $this->model->getCache()
            ->tags($this->model->cacheTags)->flush();
    }
}
