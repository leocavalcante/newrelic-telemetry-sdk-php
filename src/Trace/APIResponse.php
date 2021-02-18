<?php declare(strict_types=1);

namespace NewRelic\Trace;

use NewRelic\APIResponse as AbstractResponse;

final class APIResponse extends AbstractResponse
{
    /**
     * @var array<array-key, string>
     */
    protected array $messages = [
        202 => 'Data accepted.',
        400 => 'The structure of the request was invalid. Errors with query parameters, etc.',
        403 => 'Authentication error. May occur with an invalid license key or if you lack necessary entitlement to use the Trace API.',
        404 => 'The request path is incorrect.',
        405 => 'For any request method other than POST.',
        408 => 'The request took too long to reach the endpoint.',
        411 => 'The Content-Length header wasn’t included.',
        413 => 'The payload was too big.',
        414 => 'The request URI was too long.',
        415 => 'The Content-Type or Content-Encoding was invalid.',
        429 => 'The request rate quota has been exceeded.',
        431 => 'The request headers are too long.',
        '5xx' => 'There was a server error (please retry).',
    ];

    /**
     * @param int $code
     * @param array $payload
     * @return static
     */
    public static function create(int $code, array $payload): self
    {
        $response = new self($code, $payload);

        if (array_key_exists('requestId', $payload)) {
            $response->id = (string) $payload['requestId'];
        }

        return $response;
    }
}
