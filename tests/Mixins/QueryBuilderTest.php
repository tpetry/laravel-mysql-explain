<?php

declare(strict_types=1);

namespace Tpetry\LaravelMysqlExplain\Tests\Mixins;

use Illuminate\Support\Facades\DB;
use Tpetry\LaravelMysqlExplain\Facades\MysqlExplain;
use Tpetry\LaravelMysqlExplain\Tests\TestCase;

class QueryBuilderTest extends TestCase
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
        $builder = DB::table('test123');
        MysqlExplain::shouldReceive('submitBuilder')
            ->once()
            ->with($builder)
            ->andReturn('https://dummy-url-f6V7VImZnz.local');

        $url = $builder->explainForHumans();

        $this->assertEquals('https://dummy-url-f6V7VImZnz.local', $url);
    }

    public function test_visual_explain(): void
    {
        $builder = DB::table('test123');
        MysqlExplain::shouldReceive('submitBuilder')
            ->once()
            ->with($builder)
            ->andReturn('https://dummy-url-f6V7VImZnz.local');

        $url = $builder->visualExplain();

        $this->assertEquals('https://dummy-url-f6V7VImZnz.local', $url);
    }
}
