# Laravel Eloquent cache

[![Total Downloads](https://poser.pugx.org/alex433/laravel-eloquent-cache/downloads)](https://packagist.org/packages/alex433/laravel-eloquent-cache)
[![Latest Stable Version](https://poser.pugx.org/alex433/laravel-eloquent-cache/version)](https://packagist.org/packages/alex433/laravel-eloquent-cache)
[![Latest Unstable Version](https://poser.pugx.org/alex433/laravel-eloquent-cache/v/unstable)](//packagist.org/packages/alex433/laravel-eloquent-cache)
[![License](https://poser.pugx.org/alex433/laravel-eloquent-cache/license)](https://packagist.org/packages/alex433/laravel-eloquent-cache)

Laravel's Eloquent models caching

## Installation

Install via [composer](https://getcomposer.org/) :

`composer require alex433/laravel-eloquent-cache`

## How it works

When Eloquent fetches models by primary key, the SQL query result are cached.
Subsequently, when eloquent fetches a model by primary key, the cached result will be used.
The cache entry will be flushed when you create, update, or delete a model instance.
## Usage

Use the `Cachable` trait in the models you want to cache.

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Alex433\LaravelEloquentCache\Cachable;

class Post extends Model
{
    use Cachable;
}

```
In next cases cache queries will be executed instead SQL queries. Also it do the trick for "belongs To" relations.
```php
Post::find($id); // findOrFail(), findOrNew()
Post::where('id', $id)->first(); // firstOrFail(), firstOrNew(), firstOrCreate(), firstOr()
Post::whereId($id)->first();
Post::where('id', $id)->get();
```

You can optionally define the following properties to change default trait behavior.

```php
<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Alex433\LaravelEloquentCache\Cachable;

class User extends Authenticatable
{
    use Notifiable,
        Cachable;

    /**
     * Cache TTL in seconds. Defaults indefinitely
     *
     * @var int $cacheTtl
     */
    public $cacheTtl = 3600;

    /**
     * Cache store name. Defaults default cache connection
     *
     * @var string $cacheStore
     */
    public $cacheStore = 'redis';

    /**
     * Cache tags. Defaults no tags
     *
     * @var array $cacheTags
     */
    public $cacheTags = ['users'];
}
```

To invalidate the cache entry for a model instance, use `forget` method.

```php
User::find($id)->forget();

// or

User::find($id)->forget()->refresh();
```

When cache tags is used, you can flush the cache for a model, use the `flushCache` method.

```php
User::flushCache();

// or

User::find($id)->flushCache();
```
