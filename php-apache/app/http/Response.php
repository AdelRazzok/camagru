<?php

namespace app\http;

class Response
{
    private array $headers;
    private string $body;
    private int $statusCode;

    public function __construct($headers, $body, $statusCode = 200)
    {
        $this->headers = $headers;
        $this->body = $body;
        $this->statusCode = $statusCode;
    }

    public function addHeader(string $header, string $value): void
    {
        $this->headers[$header] = $value;
    }

    public function send(): void
    {
        http_response_code($this->statusCode);

        foreach ($this->headers as $header => $value) {
            header("$header: $value");
        }

        echo $this->body;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
