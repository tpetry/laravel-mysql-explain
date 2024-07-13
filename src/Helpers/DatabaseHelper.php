<?php

declare(strict_types=1);

namespace Tpetry\MysqlExplain\Helpers;

use Illuminate\Database\Connection;
use Illuminate\Support\Str;

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
        foreach ($db->prepareBindings($bindings) as $binding) {
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
     */
    public function queryScalar(Connection $db, string $sql, array $bindings = []): string
    {
        if (method_exists($db, 'scalar')) {
            return (string) $db->scalar($sql, $bindings);
        }

        $record = (array) $db->selectOne($sql, $bindings);

        return (string) reset($record);
    }
}
