# New Relic Telemetry SDK for PHP ![CI](https://github.com/leocavalcante/newrelic-telemetry-sdk-php/workflows/CI/badge.svg?branch=main)

**Unofficial** PHP library for sending telemetry data to New Relic.

| Status | API | Description |
| --- | --- | --- |
| ✅ | Trace API | Used to send [distributed tracing](https://docs.newrelic.com/docs/understand-dependencies/distributed-tracing/get-started/introduction-distributed-tracing#) data to New Relic (New Relic's format). |
| ✅ | Metric API | Used to send [metric data](https://docs.newrelic.com/docs/using-new-relic/data/understand-data/new-relic-data-types#dimensional-metrics) to New Relic. |
| ✅ | Event API | Is one way to report [custom events](https://docs.newrelic.com/docs/insights/insights-data-sources/custom-data/report-custom-event-data) to New Relic. |
| - | Log API | **Not planned.** |

- ⚠️ Heavily under development, but open-sourced seeking for contributions.
- It **is not** an agent wrapper, it calls the New Relic [Ingest APIs](https://docs.newrelic.com/docs/telemetry-data-platform/ingest-manage-data/ingest-apis).

## Usage

### Example

Sending a [Gauge](https://docs.newrelic.com/docs/telemetry-data-platform/ingest-manage-data/understand-data/metric-data-type) metric.

```php
use NewRelic\Adapter;
use NewRelic\Metric;

$metric_api = new Metric\API(new Adapter\Curl(getenv('NR_API_KEY')));
$metric_api->setCommonAttrs(['service.name' => 'PHP-SDK']);
$metric_api->send(new Metric\Gauge('memory.heap', 2.3));

$response = $metric_api->commit();

if ($response->isOk()) {
    echo sprintf("Request ID: %s\n", $response->getId());
} else {
    echo sprintf("Metric send error: %s\n", $response->getMessage());
}
```

## Companion resources

- [New Relic data dictionary](https://docs.newrelic.com/attribute-dictionary)
- [Ingest APIs](https://docs.newrelic.com/docs/telemetry-data-platform/ingest-manage-data/ingest-apis)
- [OpenTelemetry Spec](https://github.com/open-telemetry/opentelemetry-specification/tree/master/specification/resource/semantic_conventions)

### Tips

- Make sure you are including [service.instance.id](https://github.com/open-telemetry/opentelemetry-specification/blob/master/specification/resource/semantic_conventions/README.md#service) when reporting your traces and/or metrics.

## Why

Main reasons includes:
- Too many [segfaults](https://www.google.com/search?q=newrelic+segfault) with the regular agent. 
- Even for simple use cases the regular agent doesn't play well with Swoole. This small snippet is enough to throw a segfault:
  ```php
  Co\run(static function () {
    go(static function () {
      (new Co\Http\Client('swoole.co.uk'))->get('/');
    });
  });
  ```
- There are [other SDKs for other languages](https://docs.newrelic.com/docs/telemetry-data-platform/get-started/capabilities/telemetry-sdks-send-custom-telemetry-data-new-relic), this is an unofficial PHP version.
- Could be used to build a New Relic exporter for the upcoming [OpenTelemetry PHP library](https://github.com/open-telemetry/opentelemetry-php).
