# New Relic Telemetry SDK for PHP ![CI](https://github.com/leocavalcante/newrelic-telemetry-sdk-php/workflows/CI/badge.svg?branch=main)

**Unofficial** PHP library for sending telemetry data to New Relic.

- ⚠️ Heavily under development, but open-sourced seeking for contributions.
- It **is not** an agent wrapper, it calls the New Relic [Ingest APIs](https://docs.newrelic.com/docs/telemetry-data-platform/ingest-manage-data/ingest-apis).

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

https://docs.newrelic.com/docs/telemetry-data-platform/ingest-manage-data/ingest-apis

# Why

Main reasons includes:
- Too many [segfaults](https://www.google.com/search?q=newrelic+segfault) with the regular agent. 
- The regular agent doesn't play well with Swoole:
  ```php
  Co\run(static function () {
    go(static function () {
      (new Co\Http\Client('swoole.co.uk'))->get('/');
    });
  });
  ```
  This extremely simple snippet is enough to throw a segfault.
- There are other SDKs for other languages, this is an unofficial PHP version.
