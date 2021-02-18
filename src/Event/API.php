<?php declare(strict_types=1);

namespace NewRelic\Event;

use NewRelic\Adapter\AdapterInterface;
use NewRelic\APIInterface;
use NewRelic\APIResponseInterface;

/**
 * @see https://docs.newrelic.com/docs/telemetry-data-platform/ingest-manage-data/ingest-apis/use-event-api-report-custom-events
 */
class API implements APIInterface
{
    private const ENDPOINT = 'https://insights-collector.newrelic.com/v1/accounts/%s/events';

    private AdapterInterface $adapter;
    private string $accountId;
    private array $events;
    /** @var array<string, mixed> */
    private array $commonEventData;

    /**
     * @param string $accountId
     * @param AdapterInterface $adapter
     * @param array $events
     * @param array<string, mixed> $commonEventData
     */
    public function __construct(
        string $accountId,
        AdapterInterface $adapter,
        array $events = [],
        array $commonEventData = []
    ) {
        $this->accountId = $accountId;
        $this->adapter = $adapter;
        $this->events = $events;
        $this->commonEventData = $commonEventData;
    }

    public function send(string $eventType, array $eventData = []): self
    {
        $this->events[] = array_merge(['eventType' => $eventType], array_merge($this->getCommonEventData(), $eventData));
        return $this;
    }

    public function commit(): APIResponseInterface
    {
        $result = $this->adapter->http(
            sprintf(self::ENDPOINT, $this->accountId),
            $this->events
        );
        return APIResponse::create($result->getCode(), $result->getPayload());
    }

    /**
     * @return array<string, mixed>
     */
    public function getCommonEventData(): array
    {
        return $this->commonEventData;
    }

    /**
     * @param array<string, mixed> $commonEventData
     */
    public function setCommonEventData(array $commonEventData): void
    {
        $this->commonEventData = $commonEventData;
    }
}
