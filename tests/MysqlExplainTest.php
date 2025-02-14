<?php

declare(strict_types=1);

namespace Tpetry\LaravelMysqlExplain\Tests;

use Illuminate\Database\Connection;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\DB;
use Mockery;
use Mockery\MockInterface;
use Tpetry\LaravelMysqlExplain\LaravelQuery;
use Tpetry\LaravelMysqlExplain\MysqlExplain;
use Tpetry\LaravelMysqlExplain\NotMysqlException;
use Tpetry\PhpMysqlExplain\Api\Client;
use Tpetry\PhpMysqlExplain\Api\Result;
use Tpetry\PhpMysqlExplain\Metrics\Collector;
use Tpetry\PhpMysqlExplain\Metrics\Metrics;

class MysqlExplainTest extends TestCase
{
    public function test_submit_builder_forwards_call(): void
    {
        $builder = DB::connection()->table('test')->where('col', 1);
        $mysqlExplain = Mockery::mock(MysqlExplain::class.'[submitQuery]')
            ->shouldReceive('submitQuery')
            ->once()
            ->withArgs([$builder->getConnection(), $builder->toSql(), $builder->getBindings()])
            ->andReturn('https://dummy-url-LTGq4mDWlo.local/PTzphBiWQW')
            ->getMock();

        $url = $mysqlExplain->submitBuilder($builder);

        $this->assertEquals('https://dummy-url-LTGq4mDWlo.local/PTzphBiWQW', $url);
    }

    public function test_submit_query_fails_when_mariadb_feature_is_detected(): void
    {
        $this->expectException(NotMysqlException::class);
        $this->expectExceptionMessage('Only queries on mysql databases can be analyzed. mariadb query given.');

        $connection = Mockery::mock(Connection::class)
            ->shouldReceive('getDriverName')
            ->andReturn('mysql')
            ->shouldReceive('select')
            ->withArgs(['SELECT * FROM seq_1_to_1'])
            ->getMock();

        (new MysqlExplain)->submitQuery($connection, 'SELECT 1');
    }

    public function test_submit_query_fails_when_passed_non_mysql_connection(): void
    {
        $this->expectException(NotMysqlException::class);
        $this->expectExceptionMessage('Only queries on mysql databases can be analyzed. mariadb query given.');

        $connection = Mockery::mock(Connection::class)
            ->shouldReceive('getDriverName')
            ->andReturn('mariadb')
            ->getMock();

        (new MysqlExplain)->submitQuery($connection, 'SELECT 1');
    }

    public function test_submit_query_fails_when_passed_non_pdo_connection(): void
    {
        $this->expectException(NotMysqlException::class);
        $this->expectExceptionMessage('Only queries on mysql databases can be analyzed. unknown query given.');

        (new MysqlExplain)->submitQuery(Mockery::mock(ConnectionInterface::class), 'SELECT 1');
    }

    public function test_submit_query_returns_url(): void
    {
        $connection = DB::connection('mysql');
        $sql = 'SELECT * FROM customer WHERE last_name = ?';
        $parameters = ['SMITH'];

        $query = new LaravelQuery($connection, $sql, $parameters);
        $metrics = new Metrics(
            query: $query,
            version: '9.0',
            explainJson: '{ "query_block": { "select_id": 1, "message": "Impossible WHERE" } }',
            explainTree: '-> Zero rows (Impossible WHERE)  (cost=0..0 rows=0)',
        );

        $this->app->bind(LaravelQuery::class, function ($_app, $params) use ($connection, $sql, $parameters, $query) {
            $this->assertEquals($connection, $params['connection']);
            $this->assertEquals($sql, $params['sql']);
            $this->assertEquals($parameters, $params['parameters']);

            return $query;
        });
        $this->mock(Collector::class, function (MockInterface $mock) use ($query, $metrics) {
            $mock->shouldReceive('execute')
                ->once()
                ->withArgs([$query])
                ->andReturn($metrics);
        });
        $this->mock(Client::class, function (MockInterface $mock) use ($metrics) {
            $mock->shouldReceive('submit')
                ->once()
                ->withArgs([$metrics])
                ->andReturn(new Result('https://dummy-url-i5e6Kp3vJm.local/wowOFpsM2O'));
        });

        $url = (new MysqlExplain)->submitQuery($connection, $sql, $parameters);

        $this->assertEquals('https://dummy-url-i5e6Kp3vJm.local/wowOFpsM2O', $url);
    }
}
