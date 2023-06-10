<?php

declare(strict_types=1);

namespace Tpetry\MysqlExplain\Helpers;

use Illuminate\Database\Connection;
use Illuminate\Support\Str;
use PDO;
use PDOStatement;

/**
 * @internal
 */
class DatabaseHelper
{
    /**
     * @param  mixed[]  $bindings
     */
    public function buildRawSql(Connection $db, string $sql, array $bindings = []): string
    {
        $escapedBindings = [];
        foreach ($bindings as $binding) {
            if ($binding === null) {
                $escapedBindings[] = 'null';
            } elseif (is_int($binding) || is_float($binding)) {
                $escapedBindings[] = (string) $binding;
            } elseif (is_bool($binding)) {
                $escapedBindings[] = $binding ? '1' : '0';
            } else {
                $escapedBindings[] = $db->getPdo()->quote(strval($binding));
            }
        }

        return Str::replaceArray('?', $escapedBindings, $sql);
    }

    public function driverName(Connection $db): string
    {
        return $db->getDriverName();
    }

    /**
     * @param  mixed[]  $bindings
     * @return array<int, array<string, int|float|string|null>>
     */
    public function queryAssoc(Connection $db, string $sql, array $bindings = []): array
    {
        return $this->executeQuery($db, $sql, $bindings, fn (PDOStatement $statement) => $statement->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * @param  mixed[]  $bindings
     * @return int|float|string|null
     */
    public function queryScalar(Connection $db, string $sql, array $bindings = []): mixed
    {
        return $this->executeQuery($db, $sql, $bindings, function (PDOStatement $statement): mixed {
            /** @var int|float|string|null $value */
            $value = $statement->fetchColumn();

            return $value;
        });
    }

    /**
     * @template T
     *
     * @param  mixed[]  $bindings
     * @param  (callable(\PDOStatement): T)  $fn
     * @return T
     */
    private function executeQuery(Connection $db, string $sql, array $bindings, callable $fn): mixed
    {
        $db->reconnectIfMissingConnection();
        $pdo = $db->getPdo();

        $emulatePrepares = $pdo->getAttribute(PDO::ATTR_EMULATE_PREPARES);
        $errorMode = $pdo->getAttribute(PDO::ATTR_ERRMODE);
        try {
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $statement = $pdo->prepare($sql);
            $db->bindValues($statement, $bindings);
            $statement->execute();

            return $fn($statement);
        } finally {
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, $emulatePrepares);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, $errorMode);
        }
    }
}
