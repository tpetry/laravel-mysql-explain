<?php

declare(strict_types=1);

namespace Tpetry\MysqlExplain\Tests;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\DB;
use Mockery\MockInterface;
use Tpetry\MysqlExplain\Exceptions\NotMysqlException;
use Tpetry\MysqlExplain\Helpers\ApiHelper;
use Tpetry\MysqlExplain\Helpers\DatabaseHelper;
use Tpetry\MysqlExplain\MysqlExplain;
use Tpetry\MysqlExplain\Values\QueryMetrics;

class MysqlExplainTest extends TestCase
{
    public function testSubmitBuilderForwardsCall(): void
    {
        $builder = DB::connection()->table('test')->where('col', 1);
        $mysqlExplain = $this->partialMock(MysqlExplain::class, function (MockInterface $mock): void {
            $mock->shouldReceive('submitBuilder')
                // TODO: argument expectations never worked
                //->withArgs([$builder->getConnection(), $builder->toSql(), $builder->getBindings()])
                ->andReturn('https://dummy-url-LTGq4mDWlo.local/PTzphBiWQW')
                ->once();
        });

        $url = $mysqlExplain->submitBuilder($builder);

        $this->assertEquals('https://dummy-url-LTGq4mDWlo.local/PTzphBiWQW', $url);
    }

    public function testSubmitQueryCollectsMetricsAndSubmitsThem(): void
    {
        $connection = DB::connection('mysql');
        $this->mock(DatabaseHelper::class, function (MockInterface $mock) use ($connection): void {
            $mock->shouldReceive('buildRawSql')
                ->withArgs([$connection, 'SELECT * FROM customer WHERE last_name = ?', ['SMITH']])
                ->andReturn('...sql...');
            $mock->shouldReceive('driverName')
                ->with($connection)
                ->andReturn('mysql');
            $mock->shouldReceive('queryScalar')
                ->withArgs([$connection, 'SELECT VERSION()'])
                ->andReturn('...version...');
            $mock->shouldReceive('queryScalar')
                ->withArgs([$connection, 'EXPLAIN FORMAT=JSON SELECT * FROM customer WHERE last_name = ?', ['SMITH']])
                ->andReturn('...explain json...');
            $mock->shouldReceive('queryScalar')
                ->withArgs([$connection, 'EXPLAIN FORMAT=TREE SELECT * FROM customer WHERE last_name = ?', ['SMITH']])
                ->andReturn('...explain tree...');
            $mock->shouldReceive('queryAssoc')
                ->withArgs([$connection, 'EXPLAIN FORMAT=TRADITIONAL SELECT * FROM customer WHERE last_name = ?', ['SMITH']])
                ->andReturn(['...explain traditional...']);
            $mock->shouldReceive('queryAssoc')
                ->withArgs([$connection, 'SHOW WARNINGS'])
                ->andReturn(['...warnings...']);
        });
        $this->mock(ApiHelper::class, function (MockInterface $mock): void {
            $mock->shouldReceive('submitPlan')
                ->withArgs(function (QueryMetrics $arg): bool {
                    if ($arg->getQuery() !== '...sql...') {
                        return false;
                    }
                    if ($arg->getVersion() !== '...version...') {
                        return false;
                    }
                    if ($arg->getExplainTraditional() !== ['...explain traditional...']) {
                        return false;
                    }
                    if ($arg->getExplainJson() !== '...explain json...') {
                        return false;
                    }
                    if ($arg->getExplainTree() !== '...explain tree...') {
                        return false;
                    }
                    if ($arg->getWarnings() !== ['...warnings...']) {
                        return false;
                    }

                    return true;
                })
                ->andReturn('https://dummy-url-i5e6Kp3vJm.local/wowOFpsM2O');
        });

        $url = (new MysqlExplain())->submitQuery($connection, 'SELECT * FROM customer WHERE last_name = ?', ['SMITH']);

        $this->assertEquals('https://dummy-url-i5e6Kp3vJm.local/wowOFpsM2O', $url);
    }

    public function testSubmitQueryForbidsNonMysqlDatabases(): void
    {
        $this->expectException(NotMysqlException::class);
        $this->expectExceptionMessage('Only queries on mysql databases can be analyzed. pgsql query given.');

        (new MysqlExplain())->submitQuery(DB::connection('pgsql'), 'SELECT * FROM actor');
    }

    public function testSubmitQueryForbidsNonPdoConnections(): void
    {
        $connection = $this->mock(ConnectionInterface::class);

        $this->expectException(NotMysqlException::class);
        $this->expectExceptionMessage('Only queries on mysql databases can be analyzed. unknown query given.');

        (new MysqlExplain())->submitQuery($connection, 'SELECT * FROM film');
    }
}
