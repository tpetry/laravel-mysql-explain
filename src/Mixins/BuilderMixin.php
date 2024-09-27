<?php

declare(strict_types=1);

namespace Tpetry\LaravelMysqlExplain\Mixins;

use Closure;
use Tpetry\LaravelMysqlExplain\Facades\MysqlExplain;

/**
 * @internal
 */
class BuilderMixin
{
    public function ddExplainForHumans(): Closure
    {
        /** @return never-returns */
        return function () {
            /** @var \Illuminate\Contracts\Database\Query\Builder $this */
            dd(MysqlExplain::submitBuilder($this));
        };
    }

    public function ddVisualExplain(): Closure
    {
        /** @return never-returns */
        return function () {
            /** @var \Illuminate\Contracts\Database\Query\Builder $this */
            dd(MysqlExplain::submitBuilder($this));
        };
    }

    public function dumpExplainForHumans(): Closure
    {
        return function () {
            /** @var \Illuminate\Contracts\Database\Query\Builder $this */
            dump(MysqlExplain::submitBuilder($this));

            return $this;
        };
    }

    public function dumpVisualExplain(): Closure
    {
        return function () {
            /** @var \Illuminate\Contracts\Database\Query\Builder $this */
            dump(MysqlExplain::submitBuilder($this));

            return $this;
        };
    }

    public function explainForHumans(): Closure
    {
        return function (): string {
            /** @var \Illuminate\Contracts\Database\Query\Builder $this */
            return MysqlExplain::submitBuilder($this);
        };
    }

    public function visualExplain(): Closure
    {
        return function (): string {
            /** @var \Illuminate\Contracts\Database\Query\Builder $this */
            return MysqlExplain::submitBuilder($this);
        };
    }
}
