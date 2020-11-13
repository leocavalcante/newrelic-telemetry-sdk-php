<?php declare(strict_types=1);

namespace NewRelic\Event;

use NewRelic\APIResponse as AbstractResponse;
use NewRelic\APIResponseInterface;

final class APIResponse extends AbstractResponse
{
    protected array $messages = [
        400 => 'Missing or invalid content length: Unable to process empty request.',
        403 => 'Missing or invalid API key: Invalid Insert key, or the account does not have access to Insights. Register a valid Insert key.',
        408 => 'Request timed out: Request took too long to process.',
        413 => 'Content too large: Request is too large to process. Refer to the limits and restricted characters to troubleshoot.',
        415 => 'Invalid content type: Must be application/JSON. The Event API accepts any content type except multi-part/related and assumes it can be parsed to JSON.',
        429 => 'Too many requests due to rate limiting.',
        '5xx' => 'Service temporarily unavailable: Retry request',
    ];

    /**
     * @param int $code
     * @param array $payload
     * @return APIResponseInterface
     */
    public static function create(
        int $code,
        array $payload
    ): APIResponseInterface {
        $response = new self($code, $payload);

        if (array_key_exists('uuid', $payload)) {
            $response->id = (string) $payload['uuid'];
        }

        return $response;
    }
}
