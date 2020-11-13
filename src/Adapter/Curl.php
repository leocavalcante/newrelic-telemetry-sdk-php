<?php declare(strict_types=1);

namespace NewRelic\Adapter;

final class Curl implements AdapterInterface
{
    private string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function post(string $endpoint, array $data): PostResult
    {
        $ch = curl_init();
        $opts = [
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                "Api-Key: {$this->apiKey}",
                'Content-Encoding: GZIP',
            ],
            CURLOPT_POSTFIELDS => gzencode(json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE)),
        ];

        curl_setopt_array($ch, $opts);

        /** @var string|false $data */
        $data = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $error = curl_error($ch) ?: 'Unknown error';

        curl_close($ch);

        if ($data === false || $code < 200 || $code >= 500) {
            throw new PostException($error);
        }

        return new PostResult($code, $data);
    }
}
