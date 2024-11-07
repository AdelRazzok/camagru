<?php

namespace app\http;

class Request
{
    private string $uri;
    private string $method;
    private array $headers;
    private array $params;
    private array $body;
    private string $rawBody;

    public function __construct()
    {
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->initializeHeaders();
        $this->params = $_GET;
        $this->initializeBody();
    }

    private function initializeHeaders(): void
    {
        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
                $this->headers[$header] = $value;
            }
        }
    }

    private function initializeBody(): void
    {
        if ($this->method === 'POST' || !empty($_POST)) {
            $this->body = $_POST;
            return;
        }

        $this->rawBody = file_get_contents('php://input');

        if ($this->headers['Content-Type'] ?? '' === 'application/json') {
            $jsonBody = json_decode($this->rawBody, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $this->body = $jsonBody;
            }
        }
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function getBody(): array
    {
        return $this->body;
    }

    public function getRawBody(): string
    {
        return $this->rawBody;
    }
}
