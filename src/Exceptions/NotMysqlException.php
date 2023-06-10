<?php

declare(strict_types=1);

namespace Tpetry\MysqlExplain\Exceptions;

use Tpetry\MysqlExplain\MySqlExplainException;

class NotMysqlException extends MySqlExplainException
{
    public static function create(?string $driverName): self
    {
        $driverName ??= 'unknown';

        return new self("Only queries on mysql databases can be analyzed. {$driverName} query given.");
    }
}
