<?php

declare(strict_types=1);

namespace Tpetry\LaravelMysqlExplain;

use GuzzleHttp\Client as Guzzle;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\ServiceProvider;
use Tpetry\LaravelMysqlExplain\Mixins\BuilderMixin;
use Tpetry\PhpMysqlExplain\Api\Client;

class MysqlExplainServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->bind(Client::class, fn () => new Client(new Guzzle));

        EloquentBuilder::mixin(new BuilderMixin);
        QueryBuilder::mixin(new BuilderMixin);
    }
}
