<?php declare(strict_types=1);

namespace NewRelic;

interface APIResponseInterface
{
    public static function create(int $code, array $payload): self;
    public function isOk(): bool;
    public function isErr(): bool;
    public function getMessage(): string;
    public function getId(): ?string;
    public function getCode(): int;
    public function getPayload(): array;
}
