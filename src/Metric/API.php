<?php declare(strict_types=1);

namespace NewRelic\Metric;

use NewRelic\Adapter\AdapterInterface;
use NewRelic\APIInterface;
use NewRelic\APIResponseInterface;

/**
 * @see https://docs.newrelic.com/docs/telemetry-data-platform/ingest-manage-data/ingest-apis/report-metrics-metric-api
 */
final class API implements APIInterface
{
    private const ENDPOINT = 'https://metric-api.newrelic.com/metric/v1';

    private AdapterInterface $adapter;
    private array $common;
    private array $metrics;

    public function __construct(AdapterInterface $adapter, array $common = [], array $metrics = [])
    {
        $this->adapter = $adapter;
        $this->common = $common;
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
            'metrics' => $this->metrics,
        ];

        if (!empty($this->common)) {
            $data['common'] = $this->common;
        }

        $result = $this->adapter->post(self::ENDPOINT, [$data]);

        return APIResponse::create($result->getCode(), $result->getPayload());
    }

    public function setCommon(array $common): self
    {
        $this->common = $common;
        return $this;
    }
}