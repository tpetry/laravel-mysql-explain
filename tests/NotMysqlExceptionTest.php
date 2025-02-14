<?php

declare(strict_types=1);

namespace Tpetry\LaravelMysqlExplain\Tests;

use Tpetry\LaravelMysqlExplain\NotMysqlException;

class NotMysqlExceptionTest extends TestCase
{
    public function test_creates_exception_with_drivername(): void
    {
        $exception = NotMysqlException::create('mysql');

        $this->assertEquals(NotMysqlException::class, $exception::class);
        $this->assertEquals('Only queries on mysql databases can be analyzed. mysql query given.', $exception->getMessage());
    }

    public function test_creates_exception_with_missing_drivername(): void
    {
        $exception = NotMysqlException::create(null);

        $this->assertEquals(NotMysqlException::class, $exception::class);
        $this->assertEquals('Only queries on mysql databases can be analyzed. unknown query given.', $exception->getMessage());
    }
}
