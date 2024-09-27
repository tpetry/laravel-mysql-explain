<?php

declare(strict_types=1);

namespace Tpetry\LaravelMysqlExplain;

use Illuminate\Database\ConnectionInterface;
use Tpetry\PhpMysqlExplain\Queries\QueryInterface;

class LaravelQuery implements QueryInterface
{
    /**
     * @param  array<array-key,mixed>  $parameters
     */
    public function __construct(
        private ConnectionInterface $connection,
        private string $sql,
        private array $parameters,
    ) {}

    public function execute(string $sql, bool $useParams): array
    {
        $rows = match ($useParams) {
            true => $this->connection->select($sql, $this->parameters),
            false => $this->connection->select($sql),
        };

        // Laravel creates array<object> instead of array<arrays> as requested by interface.
        $rows = array_map(fn ($row) => (array) $row, $rows);

        return $rows;
    }

    public function getParameters(): array
    {
        // Transform special values like DateTimeInterface and bool to their string value.
        return $this->connection->prepareBindings($this->parameters);
    }

    public function getSql(): string
    {
        return $this->sql;
    }

    public function name(): string
    {
        return 'laravel@'.MysqlExplain::$VERSION;
    }
}
