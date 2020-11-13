<?php declare(strict_types=1);

namespace NewRelic\Test\Unit\Metric;

use Mockery;
use NewRelic\Adapter\AdapterInterface;
use NewRelic\Adapter\HttpResult;
use NewRelic\Metric\API;
use NewRelic\Metric\Gauge;

it('send metrics', function (): void {
    $metric = new Gauge('test', 123, 1234567890);

    $http_result = Mockery::mock(HttpResult::class);
    $http_result->expects('getCode')->andReturn(202);
    $http_result->expects('getPayload')->andReturn(['requestId' => 'xxxx-xxxx-xxxx-xxxx']);

    $adapter = Mockery::mock(AdapterInterface::class);
    $adapter
        ->expects('http')
        ->with('https://metric-api.newrelic.com/metric/v1', [
            [
                'common' => [
                    'attributes' => [
                        'telemetry.sdk.name' => 'newrelic-contrib',
                        'telemetry.sdk.language' => 'php',
                        'telemetry.sdk.version' => '0.1.0',
                    ],
                ],
                'metrics' => [$metric],
            ],
        ])
        ->andReturn($http_result);

    $api = new API($adapter);
    $api->send($metric);

    $response = $api->commit();
    expect($response->getId())->toBe('xxxx-xxxx-xxxx-xxxx');
});
