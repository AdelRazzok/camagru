<?php

namespace http;

class Response
{
    public const HTTP_OK = 200;
    public const HTTP_CREATED = 201;
    public const HTTP_SEE_OTHER = 303;
    public const HTTP_BAD_REQUEST = 400;
    public const HTTP_UNAUTHORIZED = 401;
    public const HTTP_FORBIDDEN = 403;
    public const HTTP_NOT_FOUND = 404;
    public const HTTP_INTERNAL_SERVER_ERROR = 500;

    private int $statusCode;
    private array $headers;
    private ?string $body;
    private string $contentType;

    public function __construct(int $statusCode = 200, array $headers = [], ?string $body = '', string $contentType = 'text/html')
    {
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->body = $body;
        $this->setContentType($contentType);
    }

    public function setContentType(string $contentType): void
    {
        $this->contentType = $contentType;
        $this->addHeader('Content-Type', $contentType);
    }

    public function addHeader(string $name, string $value): void
    {
        $this->headers[$name] = $value;
    }

    private function sendHeaders(): void
    {
        http_response_code($this->statusCode);

        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
    }

    private function sendBody(): void
    {
        if (empty($this->body)) {
            $body = $this->generateDefaultBody();
        }

        if ($this->contentType === 'text/html') {
            $body = $this->body;
        } else if ($this->contentType === 'application/json') {
            $body = json_encode($this->body);
        }
        echo $body;
    }

    private function generateDefaultBody()
    {
        if ($this->statusCode >= 400) {
            $text = 'Error: ' . $this->statusCode;
        } else {
            $text = 'Default body: ' . $this->statusCode;
        }
        return "<html><body><h1>$text</h1></body></html>";
    }

    public function send(): void
    {
        $this->sendHeaders();
        $this->sendBody();
    }
}
