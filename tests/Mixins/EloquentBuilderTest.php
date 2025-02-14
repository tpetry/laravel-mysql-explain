<?php

declare(strict_types=1);

namespace Tpetry\LaravelMysqlExplain\Tests\Mixins;

use Illuminate\Database\Eloquent\Model;
use Tpetry\LaravelMysqlExplain\Facades\MysqlExplain;
use Tpetry\LaravelMysqlExplain\Tests\TestCase;

class EloquentBuilderTest extends TestCase
{
    public function test_dd_explain_for_humans(): void
    {
        $this->markTestSkipped('how to test dump() and not exit PHPUnit on exit() call?');
    }

    public function test_dd_visual_explain(): void
    {
        $this->markTestSkipped('how to test dump() and not exit PHPUnit on exit() call?');
    }

    public function test_dump_explain_for_humans(): void
    {
        $this->markTestSkipped('how to test dump()?');
    }

    public function test_dump_visual_explain(): void
    {
        $this->markTestSkipped('how to test dump()?');
    }

    public function test_explain_for_humans(): void
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

    public function test_visual_explain(): void
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
