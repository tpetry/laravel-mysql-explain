<?php

declare(strict_types=1);

namespace Tpetry\LaravelMysqlExplain\Mixins;

use Closure;
use Illuminate\Contracts\Database\Query\Builder;
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
            /** @var Builder $this */
            dd(MysqlExplain::submitBuilder($this));
        };
    }

    public function ddVisualExplain(): Closure
    {
        /** @return never-returns */
        return function () {
            /** @var Builder $this */
            dd(MysqlExplain::submitBuilder($this));
        };
    }

    public function dumpExplainForHumans(): Closure
    {
        return function () {
            /** @var Builder $this */
            dump(MysqlExplain::submitBuilder($this));

            return $this;
        };
    }

    public function dumpVisualExplain(): Closure
    {
        return function () {
            /** @var Builder $this */
            dump(MysqlExplain::submitBuilder($this));

            return $this;
        };
    }

    public function explainForHumans(): Closure
    {
        return function (): string {
            /** @var Builder $this */
            return MysqlExplain::submitBuilder($this);
        };
    }

    public function visualExplain(): Closure
    {
        return function (): string {
            /** @var Builder $this */
            return MysqlExplain::submitBuilder($this);
        };
    }
}
