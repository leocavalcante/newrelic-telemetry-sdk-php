<?php declare(strict_types=1);

namespace NewRelic\Metric;

use NewRelic\APIResponse as AbstractResponse;

final class APIResponse extends AbstractResponse
{
    protected array $messages = [
        202 => 'Data accepted.',
        400 => 'Structure of the request is invalid.',
        403 => 'Authentication failure.',
        404 => 'The request path is incorrect.',
        405 => 'Used a request method other than POST.',
        408 => 'The request took too long to reach the endpoint.',
        411 => 'The Content-Length header wasn’t included.',
        413 => 'The payload was too big. Payloads must be under 1MB (10^6 bytes).',
        414 => 'The request URI was too long.',
        415 => 'The Content-Type or Content-Encoding was invalid.',
        429 => 'The request rate quota has been exceeded.',
        431 => 'The request headers are too long.',
        '5xx' => 'There was a server error (please retry).',
    ];

    public static function create(int $code, array $payload): self
    {
        $response = new self($code, $payload);
        $response->id = $payload['requestId'] ?? null;
        return $response;
    }
}