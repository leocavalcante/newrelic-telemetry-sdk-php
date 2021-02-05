<?php declare(strict_types=1);

namespace NewRelic\Metric;

use NewRelic\Adapter\AdapterInterface;
use NewRelic\APIInterface;
use NewRelic\APIResponseInterface;
use NewRelic\Common\CommonAttrsInterface;
use NewRelic\Common\TelemetrySdkTrait;

/**
 * @see https://docs.newrelic.com/docs/telemetry-data-platform/ingest-manage-data/ingest-apis/report-metrics-metric-api
 */
class API implements APIInterface, CommonAttrsInterface
{
    use TelemetrySdkTrait;

    private const ENDPOINT = 'https://metric-api.newrelic.com/metric/v1';
    private AdapterInterface $adapter;
    private array $metrics;

    public function __construct(
        AdapterInterface $adapter,
        array $common = [],
        array $metrics = []
    ) {
        $this->adapter = $adapter;
        $this->commonAttrs = $common;
        $this->metrics = $metrics;
    }

    public function send(Metric $metric): self
    {
        $this->metrics[] = $metric;
        return $this;
    }

    public function commit(): APIResponseInterface
    {
        $data = [
            'common' => [
                'attributes' => $this->getCommonAttrs(),
            ],
            'metrics' => $this->metrics,
        ];

        $result = $this->adapter->http(self::ENDPOINT, [$data]);

        $this->metrics = [];

        return APIResponse::create($result->getCode(), $result->getPayload());
    }
}
