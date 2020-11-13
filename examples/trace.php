<?php declare(strict_types=1);

use NewRelic\Adapter;
use NewRelic\Trace;

require_once __DIR__ . '/../vendor/autoload.php';

$trace_api = new Trace\API(new Adapter\Curl(getenv('NR_API_KEY')));

$trace_api->setCommonAttrs([
    'service.name' => 'PHP SDK SN',
    'service.instance.id' => '477acd2b-b0a6-4646-8173-7fa87d1c0393',
    'appName' => 'PHP SDK AN',
]);

$trace_api->span(new Trace\Span('123456', 'ABC', ['span.kind' => 'server', 'duration.ms' => 12.53, 'name' => '/home']));
$trace_api->span(new Trace\Span('123456', 'DEF', ['span.kind' => 'server', 'duration.ms' => 12.53, 'name' => '/auth', 'parent.id' => 'ABC']));

$response = $trace_api->commit();

if ($response->isOk()) {
    echo sprintf("Request ID: %s\n", $response->getId());
} else {
    echo sprintf("Metric send error: %s\n", $response->getMessage());
}