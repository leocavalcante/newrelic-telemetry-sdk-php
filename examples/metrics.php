<?php declare(strict_types=1);

use NewRelic\Adapter;
use NewRelic\Metric;

require_once __DIR__ . '/../vendor/autoload.php';

$metric_api = new Metric\API(new Adapter\Curl(getenv('NR_API_KEY')));

$metric_api->setCommon([
    'attributes' => [
        'appName' => 'PHP-SDK',
        'serviceName' => 'PHP-SDK',
        'entityName' => 'PHP-SDK',
        'entity.guid' => 'abc123',
        'host.name' => 'php-sdk.newrelic.dev',
    ],
]);

$metric_api->send((new Metric\Gauge('memory.heap', 2.3))->addAttribute('transactionName', 'Testing'));

$count = new Metric\Count('how.many.times', 0);
$count->record();
$count->record(2);
$count->setIntervalMs(10000);

$metric_api->send($count);

$response = $metric_api->commit();

if ($response->isOk()) {
    echo sprintf("Request ID: %s\n", $response->getId());
} else {
    echo sprintf("Metric send error: %s\n", $response->getMessage());
}