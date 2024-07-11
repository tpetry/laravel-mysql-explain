<?php

declare(strict_types=1);

namespace Tpetry\MysqlExplain;

use Exception;
use GuzzleHttp\Exception\BadResponseException;
use Throwable;

class MySqlExplainException extends Exception
{
    public static function fromBadResponseException(BadResponseException $e): self
    {
        $response = json_decode($e->getResponse()->getBody()->getContents(), true);
        if ($response) {
            if (isset($response['error'], $response['message']) && $response['error'] === 'unsupported_database') {
                return new self("Submitting EXPLAIN failed (Unsupported Datatabase: {$response['message']}).", 0, $e);
            }
            if (isset($response['error'], $response['message']) && $response['error'] === 'unsupported_query') {
                return new self("Submitting EXPLAIN failed (Unsupported Query: {$response['message']}).", 0, $e);
            }
        }

        return self::fromThrowable($e);
    }

    public static function fromThrowable(Throwable $t): self
    {
        return new self("Submitting EXPLAIN failed ({$t->getMessage()}).", 0, $t);
    }
}
