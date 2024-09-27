<?php

declare(strict_types=1);

namespace Tpetry\LaravelMysqlExplain;

use Exception;

class NotMysqlException extends Exception
{
    public static function create(?string $driverName): self
    {
        $driverName ??= 'unknown';

        return new self("Only queries on mysql databases can be analyzed. {$driverName} query given.");
    }
}
