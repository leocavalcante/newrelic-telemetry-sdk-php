<?php declare(strict_types=1);

namespace NewRelic\Test\Unit\Event;

use Mockery;
use NewRelic\Adapter\AdapterInterface;
use NewRelic\Adapter\HttpResult;
use NewRelic\Event\API;

it('send events', function () {
    $event_data = ['foo' => 'bar'];

    $http_result = Mockery::mock(HttpResult::class);
    $http_result->expects('getCode')->andReturn(202);
    $http_result->expects('getPayload')->andReturn(['uuid' => 'xxxx-xxxx-xxxx-xxxx']);

    $adapter = Mockery::mock(AdapterInterface::class);
    $adapter->expects('http')
        ->with('https://insights-collector.newrelic.com/v1/accounts/1234567890/events', [array_merge(['eventType' => 'Test'], $event_data)])
        ->andReturn($http_result);

    $api = new API('1234567890', $adapter);
    $api->send('Test', $event_data);

    $response = $api->commit();
    expect($response->getId())->toBe('xxxx-xxxx-xxxx-xxxx');
});
