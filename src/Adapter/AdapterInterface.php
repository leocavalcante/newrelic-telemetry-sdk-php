<?php declare(strict_types=1);

namespace NewRelic\Adapter;

interface AdapterInterface
{
    public function http(string $endpoint, array $data): HttpResult;
}
