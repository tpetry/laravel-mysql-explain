<?php

declare(strict_types=1);

namespace Tpetry\MysqlExplain\Values;

/**
 * @internal
 */
class QueryMetrics
{
    /**
     * The EXPLAIN FORMAT=JSON output.
     */
    private string $explainJSON;

    /**
     * The EXPLAIN FORMAT=TREE output.
     */
    private ?string $explainTree;

    /**
     * The query that generated the metrics.
     */
    private string $query;

    /**
     * The database version the executed was executed on.
     */
    private string $version;

    public function __construct(
        string $query,
        string $version,
        string $explainJson,
        ?string $explainTree = null,
    ) {
        $this->query = $query;
        $this->version = $version;
        $this->explainJSON = $explainJson;
        $this->explainTree = $explainTree;
    }

    public function getExplainJson(): string
    {
        return $this->explainJSON;
    }

    public function getExplainTree(): ?string
    {
        return $this->explainTree;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function getVersion(): string
    {
        return $this->version;
    }
}
