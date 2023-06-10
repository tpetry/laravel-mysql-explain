<?php

declare(strict_types=1);

namespace Tpetry\MysqlExplain;

use Exception;
use Throwable;

class MySqlExplainException extends Exception
{
    public static function fromException(Throwable $exception): self
    {
        return new self('Submitting query to explainmysql.com failed.', 0, $exception);
    }
}
