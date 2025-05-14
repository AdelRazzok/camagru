<?php

namespace http;

use http\exceptions\NotFoundException;
use Exception;

class ExceptionHandler
{
    public function handle(Exception $exception)
    {
        if ($exception instanceof NotFoundException) {
            return $this->renderNotFound($exception);
        }

        return $this->renderServerError($exception);
    }

    private function renderNotFound(NotFoundException $exception)
    {
        http_response_code($exception->getCode());
        require_once dirname(__DIR__) . '/views/errors/404.php';
        exit;
    }

    private function renderServerError(Exception $exception)
    {
        $statusCode = $exception->getCode() ?: 500;
        http_response_code($statusCode);

        $file = dirname(__DIR__) . "/views/errors/{$statusCode}.php";
        if (!file_exists($file)) {
            $file = dirname(__DIR__) . '/views/errors/500.php';
        }
        require_once $file;
        exit;
    }
}
