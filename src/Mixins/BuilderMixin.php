<?php

declare(strict_types=1);

namespace Tpetry\MysqlExplain\Mixins;

use Closure;
use Tpetry\MysqlExplain\Facades\MysqlExplain;

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
            $url = MysqlExplain::submitBuilder($this);
            dd($url);
        };
    }

    public function dumpExplainForHumans(): Closure
    {
        return function () {
            /** @var \Illuminate\Contracts\Database\Query\Builder $this */
            $url = MysqlExplain::submitBuilder($this);
            dump($url);

            return $this;
        };
    }

    public function explainForHumans(): Closure
    {
        return function (): string {
            /** @var \Illuminate\Contracts\Database\Query\Builder $this */
            $url = MysqlExplain::submitBuilder($this);

            return $url;
        };
    }
}
