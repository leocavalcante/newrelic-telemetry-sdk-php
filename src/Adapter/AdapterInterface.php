<?php declare(strict_types=1);

namespace NewRelic\Adapter;

interface AdapterInterface
{
    public function post(string $endpoint, array $data): PostResult;
}
