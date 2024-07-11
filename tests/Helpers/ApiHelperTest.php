<?php

declare(strict_types=1);

namespace Tpetry\MysqlExplain\Tests\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Tpetry\MysqlExplain\Helpers\ApiHelper;
use Tpetry\MysqlExplain\Tests\TestCase;
use Tpetry\MysqlExplain\Values\QueryMetrics;

class ApiHelperTest extends TestCase
{
    public function testAaa(): void
    {
        $history = [];
        $client = $this->createGuzzleMock(
            response: new Response(
                status: 200,
                headers: ['X-Foo' => 'Bar'],
                body: '{"url":"https://dummy-url-W2lDgjGDl1.local/4XvzCcPWKW"}',
            ),
            history: $history,
        );
        $apiHelper = new ApiHelper('https://api.some-random-domain.local', $client);

        $queryMetrics = new QueryMetrics(
            query: '...query...',
            version: '...version...',
            explainJson: '...explain json...',
            explainTree: '...explain tree...',
        );
        $url = $apiHelper->submitPlan($queryMetrics);

        $this->assertEquals('https://dummy-url-W2lDgjGDl1.local/4XvzCcPWKW', $url);
        $this->assertCount(1, $history);
        $this->assertEquals('POST', $history[0]['request']->getMethod());
        $this->assertEquals('https://api.some-random-domain.local/v2/explains', (string) $history[0]['request']->getUri());
        $this->assertEquals(['application/json'], $history[0]['request']->getHeader('Content-Type'));
        $this->assertEquals('{"query":"...query...","version":"...version...","explain_json":"...explain json...","explain_tree":"...explain tree..."}', $history[0]['request']->getBody());
    }

    private function createGuzzleMock(Response $response, array &$history): Client
    {
        $handlerStack = HandlerStack::create(new MockHandler([$response]));
        $handlerStack->push(Middleware::history($history));

        return new Client(['handler' => $handlerStack]);
    }
}
