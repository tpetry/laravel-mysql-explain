<?php

declare(strict_types=1);

namespace Tpetry\MysqlExplain\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Throwable;
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
        private ?string $domain = 'https://api.mysqlexplain.com',
        ?Client $client = null,
    ) {
        $this->client = $client ?? new Client();
    }

    public function submitPlan(QueryMetrics $metrics): string
    {
        $version = MysqlExplain::$VERSION;

        try {
            $response = $this->client->post('/v2/explains', [
                'base_uri' => $this->domain,
                'headers' => [
                    'User-Agent' => "tpetry/laravel-mysql-explain@{$version}",
                ],
                'json' => [
                    'query' => $metrics->getQuery(),
                    'version' => $metrics->getVersion(),
                    'explain_json' => $metrics->getExplainJson(),
                    'explain_tree' => $metrics->getExplainTree(),
                ],
            ]);

            /** @var array{url: string} $json */
            $json = json_decode($response->getBody()->getContents(), true);

            return $json['url'];
        } catch (BadResponseException $e) {
            throw MySqlExplainException::fromBadResponseException($e);
        } catch (Throwable $t) {
            throw MySqlExplainException::fromThrowable($t);
        }
    }
}
