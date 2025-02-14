<?php

declare(strict_types=1);

namespace Tpetry\LaravelMysqlExplain\Tests;

use DateTimeImmutable;
use Illuminate\Support\Facades\DB;
use Tpetry\LaravelMysqlExplain\LaravelQuery;
use Tpetry\LaravelMysqlExplain\MysqlExplain;

class LaravelQueryTest extends TestCase
{
    public function test_constructor_values(): void
    {
        $date = new DateTimeImmutable('@1199699021');
        $query = new LaravelQuery(
            connection: DB::connection(),
            sql: 'SELECT ?, ?, ?, ?',
            parameters: [
                1,
                true,
                false,
                $date,
            ],
        );

        $this->assertEquals('laravel@'.MysqlExplain::$VERSION, $query->name());
        $this->assertEquals('SELECT ?, ?, ?, ?', $query->getSql());
        $this->assertEquals([1, 1, 0, $date->format(DB::connection('mysql')->getQueryGrammar()->getDateFormat())], $query->getParameters());
    }

    public function test_execute_without_parameters(): void
    {
        $query = new LaravelQuery(DB::connection(), "SELECT 'unused'", ['not-used']);

        $rows = $query->execute('SELECT 1 AS val UNION SELECT 2 AS val', false);

        $this->assertEquals([['val' => 1], ['val' => 2]], $rows);
    }

    public function test_execute_wit_parameters(): void
    {
        $query = new LaravelQuery(DB::connection(), "SELECT 'unused'", [1, 2]);

        $rows = $query->execute('SELECT ? AS val UNION SELECT ? AS val', true);

        $this->assertEquals([['val' => 1], ['val' => 2]], $rows);
    }
}
