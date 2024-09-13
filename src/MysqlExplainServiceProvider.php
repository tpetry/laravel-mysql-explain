<?php

declare(strict_types=1);

namespace Tpetry\MysqlExplain;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\ServiceProvider;
use Tpetry\MysqlExplain\Mixins\BuilderMixin;

class MysqlExplainServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register(): void
    {
        EloquentBuilder::mixin(new BuilderMixin);
        QueryBuilder::mixin(new BuilderMixin);
    }
}
