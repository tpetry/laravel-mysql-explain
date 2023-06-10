<?php

declare(strict_types=1);

namespace Tpetry\MysqlExplain\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Tpetry\MysqlExplain\MysqlExplainServiceProvider;

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
