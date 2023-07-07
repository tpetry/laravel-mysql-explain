<?php

declare(strict_types=1);

namespace Tpetry\MysqlExplain\Tests\Helpers;

use DateTime;
use Illuminate\Database\Connection;
use PDO;
use Tpetry\MysqlExplain\Helpers\DatabaseHelper;
use Tpetry\MysqlExplain\Tests\TestCase;

class DatabaseHelperTest extends TestCase
{
    public static function provideBuildRawSqlData(): array
    {
        return [
            [
                'SELECT * FROM users WHERE user_id is ?',
                [null],
                'SELECT * FROM users WHERE user_id is null',
            ],
            [
                'SELECT * FROM users WHERE user_id = ?',
                [1],
                'SELECT * FROM users WHERE user_id = 1',
            ],
            [
                'SELECT * FROM users WHERE rate = ?',
                [1.1],
                'SELECT * FROM users WHERE rate = 1.1',
            ],
            [
                'SELECT * FROM users WHERE is_deleted = ?',
                [true],
                'SELECT * FROM users WHERE is_deleted = 1',
            ],
            [
                'SELECT * FROM users WHERE is_deleted = ?',
                [false],
                'SELECT * FROM users WHERE is_deleted = 0',
            ],
            [
                'SELECT * FROM users WHERE created_at > ?',
                [new DateTime('2023-01-01 00:00:00')],
                'SELECT * FROM users WHERE created_at > \'2023-01-01 00:00:00\'',
            ],
            [
                'SELECT * FROM users WHERE name = ?',
                ['Thai'],
                'SELECT * FROM users WHERE name = \'Thai\'',
            ],
        ];
    }

    /**
     * @dataProvider provideBuildRawSqlData
     */
    public function testBuildRawSql(string $sql, array $bindings, string $expectedSql): void
    {
        $grammar = $this->mock(\Illuminate\Database\Query\Grammars\Grammar::class);
        $grammar->shouldReceive('getDateFormat')->andReturn('Y-m-d H:i:s');
        $connection = $this->mock(Connection::class);
        $connection->shouldReceive('getQueryGrammar')->andReturn($grammar);
        $pdo = $this->getMockBuilder(PDO::class)->disableOriginalConstructor()->getMock();
        $pdo->expects($this->any())->method('quote')->willReturnCallback(static fn ($value) => "'$value'");
        $connection->shouldReceive('getPdo')->andReturn($pdo);
        $this->assertSame((new DatabaseHelper())->buildRawSql($connection, $sql, $bindings), $expectedSql);
    }
}
