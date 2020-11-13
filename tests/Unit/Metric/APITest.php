<?php declare(strict_types=1);

namespace NewRelic\Test\Unit\Metric;

use Mockery;
use NewRelic\Adapter\AdapterInterface;
use NewRelic\Adapter\PostResult;
use NewRelic\Metric\API;
use NewRelic\Metric\Gauge;

it('send metrics', function (): void {
    $metric = new Gauge('test', 123, 1234567890);

    $adapter = Mockery::mock(AdapterInterface::class);
    $adapter->shouldIgnoreMissing();
    $adapter->expects('post')
        ->with('https://metric-api.newrelic.com/metric/v1', [['metrics' => [$metric]]])
        ->andReturn(new PostResult(202, '{"requestId": "xxxx-xxxx-xxxx-xxxx"}'));

    $api = new API($adapter);
    $api->send($metric);

    $response = $api->commit();
    expect($response->getId())->toBe('xxxx-xxxx-xxxx-xxxx');
});