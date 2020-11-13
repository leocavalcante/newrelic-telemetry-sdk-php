<?php declare(strict_types=1);

namespace NewRelic\Adapter;

final class PostException extends \Exception
{
    private string $requestId;

    public function __construct(string $message = '', string $requestId = '', \Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->requestId = $requestId;
    }

    public function getRequestId(): string
    {
        return $this->requestId;
    }
}
