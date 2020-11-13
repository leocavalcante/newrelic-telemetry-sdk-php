<?php declare(strict_types=1);

namespace NewRelic\Adapter;

class HttpResult
{
    private int $code;
    private string $data;

    public function __construct(int $code, string $data)
    {
        $this->code = $code;
        $this->data = $data;
    }

    public function getPayload(): array
    {
        if (empty($this->data)) {
            return [];
        }

        $payload = json_decode($this->data, true);

        if (!is_array($payload)) {
            return [];
        }

        return $payload;
    }

    public function getCode(): int
    {
        return $this->code;
    }
}
