<?php declare(strict_types=1);

namespace NewRelic\Adapter;

use Ramsey\Uuid\Uuid;

final class Curl implements AdapterInterface
{
    private string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function http(string $endpoint, array $data): HttpResult
    {
        $ch = curl_init();
        $request_id = Uuid::uuid4()->toString();
        $body = json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);

        $opts = [
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                "Api-Key: {$this->apiKey}",
                "X-Insert-Key: {$this->apiKey}",
                'Content-Encoding: GZIP',
                'User-Agent: NewRelic-PHP-TelemetrySDK',
                "x-request-id: $request_id",
            ],
            CURLOPT_POSTFIELDS => gzencode($body),
        ];

        curl_setopt_array($ch, $opts);

        /** @var string|false $data */
        $data = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $error = curl_error($ch) ?: 'Unknown error';

        curl_close($ch);

        if ($data === false || $code < 200 || $code >= 500) {
            throw new HttpException($error, $request_id);
        }

        return new HttpResult($code, $data);
    }
}
