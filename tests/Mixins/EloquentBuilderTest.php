<?php

declare(strict_types=1);

namespace Tpetry\LaravelMysqlExplain\Tests\Mixins;

use Illuminate\Database\Eloquent\Model;
use Tpetry\LaravelMysqlExplain\Facades\MysqlExplain;
use Tpetry\LaravelMysqlExplain\Tests\TestCase;

class EloquentBuilderTest extends TestCase
{
    public function testDdExplainForHumans(): void
    {
        $this->markTestSkipped('how to test dump() and not exit PHPUnit on exit() call?');
    }

    public function testDdVisualExplain(): void
    {
        $this->markTestSkipped('how to test dump() and not exit PHPUnit on exit() call?');
    }

    public function testDumpExplainForHumans(): void
    {
        $this->markTestSkipped('how to test dump()?');
    }

    public function testDumpVisualExplain(): void
    {
        $this->markTestSkipped('how to test dump()?');
    }

    public function testExplainForHumans(): void
    {
        $model = new class extends Model {};
        $builder = $model->newQuery();

        MysqlExplain::shouldReceive('submitBuilder')
            ->once()
            ->with($builder)
            ->andReturn('https://dummy-url-A5mhRHJXvC.local/I2aifhDBCO');

        $url = $builder->explainForHumans();

        $this->assertEquals('https://dummy-url-A5mhRHJXvC.local/I2aifhDBCO', $url);
    }

    public function testVisualExplain(): void
    {
        $model = new class extends Model {};
        $builder = $model->newQuery();

        MysqlExplain::shouldReceive('submitBuilder')
            ->once()
            ->with($builder)
            ->andReturn('https://dummy-url-A5mhRHJXvC.local/I2aifhDBCO');

        $url = $builder->visualExplain();

        $this->assertEquals('https://dummy-url-A5mhRHJXvC.local/I2aifhDBCO', $url);
    }
}
