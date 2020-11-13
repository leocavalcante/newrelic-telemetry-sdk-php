<?php declare(strict_types=1);

namespace NewRelic;

abstract class APIResponse implements APIResponseInterface
{
    private const DEFAULT_MESSAGE = 'Unidentified status code (%d), please troubleshoot at https://docs.newrelic.com/docs/telemetry-data-platform/ingest-manage-data/ingest-apis/';

    protected int $code;
    protected array $payload;
    protected ?string $id;
    /** @var array<string> */
    protected array $messages;

    protected function __construct(int $code, array $payload)
    {
        $this->code = $code;
        $this->payload = $payload;
        $this->id = null;
        $this->messages = [];
    }

    public function getMessage(): string
    {
        if ($this->code >= 500) {
            return $this->messages['5xx'];
        }

        return $this->messages[$this->code] ?? sprintf(self::DEFAULT_MESSAGE, $this->code);
    }

    public function isOk(): bool
    {
        return $this->code >= 200 && $this->code < 400;
    }

    public function isErr(): bool
    {
        return !$this->isOk();
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function getId(): ?string
    {
        return $this->id;
    }
}