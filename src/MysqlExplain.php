<?php

declare(strict_types=1);

namespace Tpetry\MysqlExplain;

use Illuminate\Database\Connection;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Tpetry\MysqlExplain\Exceptions\NotMysqlException;
use Tpetry\MysqlExplain\Helpers\ApiHelper;
use Tpetry\MysqlExplain\Helpers\DatabaseHelper;
use Tpetry\MysqlExplain\Values\QueryMetrics;

class MysqlExplain
{
    public static string $VERSION = '1.0.0';

    /**
     * @param  \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder|\Illuminate\Contracts\Database\Query\Builder  $builder
     */
    public function submitBuilder($builder): string
    {
        return $this->submitQuery(
            $builder->getConnection(),
            $builder->toSql(),
            $builder->getBindings(),
        );
    }

    /**
     * @param  mixed[]  $bindings
     */
    public function submitQuery(ConnectionInterface $connection, string $sql, array $bindings = []): string
    {
        $apiHelper = app()->make(ApiHelper::class);

        $metrics = $this->collectQueryMetrics($connection, $sql, $bindings);
        $url = $apiHelper->submitPlan($metrics);

        return $url;
    }

    /**
     * @param  mixed[]  $bindings
     */
    private function collectQueryMetrics(ConnectionInterface $connection, string $sql, array $bindings = []): QueryMetrics
    {
        // In reality all connection interfaces should be classes of Connection. But as DB::connection() returns
        // a connection interface this method also should accept one to not generate PHPStan errors for users of the
        // library.
        if (! $connection instanceof Connection) {
            throw NotMysqlException::create(null);
        }

        // Queries are not executed with the standard Laravel database functions because those metric queries should not
        // trigger a Laravel QueryExecuted event
        $db = app()->make(DatabaseHelper::class);
        if ($db->driverName($connection) !== 'mysql') {
            throw NotMysqlException::create($db->driverName($connection));
        }

        // Laravel 11 added a new MariaDB database driver but older Laravel versions handle MySQL and MariaDB with the
        // same driver. This query uses a feature implemented in MariaDB 10.10 (the first one with a different EXPLAIN
        // output) to detect MariaDB which is unsupported.
        try {
            $db->queryScalar($connection, 'SELECT * FROM seq_1_to_1');
            throw NotMysqlException::create('mariadb');
        } catch (QueryException) {
            // This exception is expected when using MySQL as sequence tables are not available. So the exception gets
            // silenced as the check for MySQL has succeeded.
        }

        $query = $db->buildRawSql($connection, $sql, $bindings);
        $version = $db->queryScalar($connection, 'SELECT VERSION()');
        $explainJson = $db->queryScalar($connection, "EXPLAIN FORMAT=JSON {$sql}", $bindings);
        $explainTree = rescue(fn () => $db->queryScalar($connection, "EXPLAIN FORMAT=TREE {$sql}", $bindings), null, false);

        return new QueryMetrics(
            query: $query,
            version: $version,
            explainJson: $explainJson,
            explainTree: $explainTree,
        );
    }
}
