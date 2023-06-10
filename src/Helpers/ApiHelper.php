<?php

declare(strict_types=1);

namespace Tpetry\MysqlExplain\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Tpetry\MysqlExplain\MysqlExplain;
use Tpetry\MysqlExplain\MySqlExplainException;
use Tpetry\MysqlExplain\Values\QueryMetrics;

/**
 * @internal
 */
class ApiHelper
{
    private Client $client;

    public function __construct(
        private ?string $domain = 'https://explainmysql.com',
        ?Client $client = null,
    ) {
        $this->client = $client ?? new Client();
    }

    public function submitPlan(QueryMetrics $metrics): string
    {
        $version = MysqlExplain::$VERSION;

        try {
            $response = $this->client->post('/api/v1/plans', [
                'base_uri' => $this->domain,
                'headers' => [
                    'User-Agent' => "tpetry/laravel-mysql-explain@{$version}",
                ],
                'json' => [
                    'query' => $metrics->getQuery(),
                    'version' => $metrics->getVersion(),
                    'explain_traditional' => $metrics->getExplainTraditional(),
                    'explain_json' => $metrics->getExplainJson(),
                    'explain_tree' => $metrics->getExplainTree(),
                    'warnings' => $metrics->getWarnings(),
                ],
            ]);

            /** @var array{url: string} $json */
            $json = json_decode($response->getBody()->getContents(), true);

            return $json['url'];
        } catch (GuzzleException $e) {
            throw MySqlExplainException::fromException($e);
        }
    }
}
