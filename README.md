# New Relic Telemetry SDK for PHP

**Unofficial** PHP library for sending telemetry data to New Relic.

```php
use NewRelic\Adapter;
use NewRelic\Metric;

$metric_api = new Metric\API(new Adapter\Curl(getenv('NR_API_KEY')));

$metric_api->setCommon([
    'attributes' => [
        'app.name' => 'PHP-SDK',
        'host.name' => 'php-sdk.newrelic.dev',
    ],
]);

$metric_api->send(new Metric\Gauge('memory.heap', 2.3));
$response = $metric_api->commit();

if ($response->isOk()) {
    echo sprintf("Request ID: %s\n", $response->getId());
} else {
    echo sprintf("Metric send error: %s\n", $response->getMessage());
}
```

https://docs.newrelic.com/docs/telemetry-data-platform/ingest-manage-data