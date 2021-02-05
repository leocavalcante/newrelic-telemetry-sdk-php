<?php declare(strict_types=1);

namespace NewRelic\Trace;

use NewRelic\Adapter\AdapterInterface;
use NewRelic\APIInterface;
use NewRelic\APIResponseInterface;
use NewRelic\Common\CommonAttrsInterface;
use NewRelic\Common\TelemetrySdkTrait;

/**
 * @see https://docs.newrelic.com/docs/understand-dependencies/distributed-tracing/trace-api/report-new-relic-format-traces-trace-api
 */
class API implements APIInterface, CommonAttrsInterface
{
    use TelemetrySdkTrait;

    private const ENDPOINT = 'https://trace-api.newrelic.com/trace/v1';
    private AdapterInterface $adapter;
    private array $spans;

    public function __construct(AdapterInterface $adapter, array $common = [], array $spans = [])
    {
        $this->adapter = $adapter;
        $this->commonAttrs = $common;
        $this->spans = $spans;
    }

    public function span(Span $span): self
    {
        $this->spans[] = $span;
        return $this;
    }

    public function commit(): APIResponseInterface
    {
        $data = [
            'common' => [
                'attributes' => $this->getCommonAttrs(),
            ],
            'spans' => $this->spans,
        ];

        $result = $this->adapter->http(self::ENDPOINT, [$data]);

        $this->spans = [];

        return APIResponse::create($result->getCode(), $result->getPayload());
    }
}
