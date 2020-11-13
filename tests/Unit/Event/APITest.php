<?php declare(strict_types=1);

namespace NewRelic\Test\Unit\Event;

use Mockery;
use NewRelic\Adapter\AdapterInterface;
use NewRelic\Adapter\HttpResult;
use NewRelic\Event\API;

it('send evens', function () {
    $event_data = ['foo' => 'bar'];

    $adapter = Mockery::mock(AdapterInterface::class);
    $adapter->shouldIgnoreMissing();
    $adapter->expects('post')
        ->with('https://insights-collector.newrelic.com/v1/accounts/1234567890/events', [array_merge(['eventType' => 'Test'], $event_data)])
        ->andReturn(new HttpResult(202, '{"uuid": "xxxx-xxxx-xxxx-xxxx"}'));

    $api = new API('1234567890', $adapter);
    $api->send('Test', $event_data);

    $response = $api->commit();
    expect($response->getId())->toBe('xxxx-xxxx-xxxx-xxxx');
});
