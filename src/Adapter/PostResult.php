<?php declare(strict_types=1);

namespace NewRelic\Adapter;

final class PostResult
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

        return json_decode($this->data, true);
    }

    public function getCode(): int
    {
        return $this->code;
    }
}