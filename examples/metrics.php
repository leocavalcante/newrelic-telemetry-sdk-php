<?php declare(strict_types=1);

use NewRelic\Adapter;
use NewRelic\Metric;

require_once __DIR__ . '/../vendor/autoload.php';

$metric_api = new Metric\API(new Adapter\Curl(getenv('NR_API_KEY')));

$metric_api->setCommon([
    'attributes' => [
        'app.name' => 'PHP-SDK',
        'host.name' => 'php-sdk.newrelic.dev',
    ],
]);

$metric_api->send(new Metric\Gauge('memory.head', 2.3));
$response = $metric_api->commit();

if ($response->isOk()) {
    echo sprintf("Request ID: %s\n", $response->getId());
} else {
    echo sprintf("Metric send error: %s\n", $response->getMessage());
}