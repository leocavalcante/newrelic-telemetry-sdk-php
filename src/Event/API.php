<?php declare(strict_types=1);

namespace NewRelic\Event;

use NewRelic\Adapter\AdapterInterface;
use NewRelic\APIInterface;
use NewRelic\APIResponseInterface;

/**
 * @see https://docs.newrelic.com/docs/telemetry-data-platform/ingest-manage-data/ingest-apis/use-event-api-report-custom-events
 */
final class API implements APIInterface
{
    private const ENDPOINT = 'https://insights-collector.newrelic.com/v1/accounts/%s/events';

    private AdapterInterface $adapter;
    private string $accountId;
    private array $events;

    public function __construct(string $accountId, AdapterInterface $adapter, array $events = [])
    {
        $this->accountId = $accountId;
        $this->adapter = $adapter;
        $this->events = $events;
    }

    public function send(string $eventType, array $eventData = []): self
    {
        $this->events[] = array_merge(['eventType' => $eventType], $eventData);
        return $this;
    }

    public function commit(): APIResponseInterface
    {
        $result = $this->adapter->post(sprintf(self::ENDPOINT, $this->accountId), $this->events);
        return APIResponse::create($result->getCode(), $result->getPayload());
    }
}