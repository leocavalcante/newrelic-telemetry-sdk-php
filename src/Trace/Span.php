<?php declare(strict_types=1);

namespace NewRelic\Trace;

final class Span implements \JsonSerializable
{
    private string $traceId;
    private string $id;
    private array $attributes;

    public function __construct(string $traceId, string $id, array $attributes = [])
    {
        $this->traceId = $traceId;
        $this->id = $id;
        $this->attributes = $attributes;
    }

    public function jsonSerialize(): array
    {
        return [
            'trace.id' => $this->traceId,
            'id' => $this->id,
            'attributes' => $this->attributes,
        ];
    }
}