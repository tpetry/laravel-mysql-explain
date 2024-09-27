<?php

declare(strict_types=1);

namespace Tpetry\LaravelMysqlExplain;

use Illuminate\Container\Container;
use Illuminate\Database\Connection;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\QueryException;
use Tpetry\PhpMysqlExplain\Api\Client;
use Tpetry\PhpMysqlExplain\Metrics\Collector;

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
        // In reality all connection interfaces should be classes of Connection. But as DB::connection() returns
        // a connection interface this method also should accept one to not generate PHPStan errors for users of the
        // library.
        if (! $connection instanceof Connection) {
            throw NotMysqlException::create(null);
        }

        // Queries are not executed with the standard Laravel database functions because those metric queries should not
        // trigger a Laravel QueryExecuted event
        if ($connection->getDriverName() !== 'mysql') {
            throw NotMysqlException::create($connection->getDriverName());
        }

        // Laravel 11 added a new MariaDB database driver but older Laravel versions handle MySQL and MariaDB with the
        // same driver. This query uses a feature implemented in MariaDB 10.10 (the first one with a different EXPLAIN
        // output) to detect MariaDB which is unsupported.
        try {
            $connection->select('SELECT * FROM seq_1_to_1');
            throw NotMysqlException::create('mariadb');
        } catch (QueryException) {
            // This exception is expected when using MySQL as sequence tables are not available. So the exception gets
            // silenced as the check for MySQL has succeeded.
        }

        $container = Container::getInstance();
        $query = $container->make(LaravelQuery::class, ['connection' => $connection, 'sql' => $sql, 'parameters' => $bindings]);
        $metrics = $container->make(Collector::class)->execute($query);
        $result = $container->make(Client::class)->submit($metrics);

        return $result->getUrl();
    }
}
