<?php

declare(strict_types=1);

namespace Tpetry\LaravelMysqlExplain\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Tpetry\LaravelMysqlExplain\MysqlExplainServiceProvider;

class TestCase extends Orchestra
{
    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'mysql');
    }

    protected function getPackageProviders($app)
    {
        return [
            MysqlExplainServiceProvider::class,
        ];
    }
}
