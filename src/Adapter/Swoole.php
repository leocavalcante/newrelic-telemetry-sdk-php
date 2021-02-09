<?php declare(strict_types=1);

namespace NewRelic\Adapter;

use Ramsey\Uuid\Uuid;

final class Swoole implements AdapterInterface
{
    private string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function http(string $endpoint, array $data): HttpResult
    {
        $host = parse_url($endpoint, PHP_URL_HOST);
        $path = parse_url($endpoint, PHP_URL_PATH);

        $request_id = Uuid::uuid4()->toString();
        $body = json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);

        $client = new \Swoole\Coroutine\Http\Client($host, 443, true);

        $client->setHeaders([
            'Content-Type'=> 'application/json',
            'Api-Key' => $this->apiKey,
            'X-Insert-Key' => $this->apiKey,
            'Content-Encoding' => 'GZIP',
            'User-Agent' => 'NewRelic-PHP-TelemetrySDK',
            'x-request-id' => $request_id,
        ]);

        if (!$client->post($path, gzencode($body))) {
            throw new HttpException((string) $client->errMsg, $request_id);
        }

        return new HttpResult((int) $client->getStatusCode(), (string) $client->getBody());
    }
}