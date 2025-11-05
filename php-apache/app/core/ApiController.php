<?php

namespace core;

use http\Response;

abstract class ApiController
{
  public function jsonResponse(array $data, int $status = Response::HTTP_OK): void
  {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    exit;
  }

  public function jsonError(string $message, int $status): void
  {
    $this->jsonResponse(['error' => $message], $status);
  }
}
