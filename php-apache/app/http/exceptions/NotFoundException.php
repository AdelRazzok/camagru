<?php

namespace http\exceptions;

use Exception;

class NotFoundException extends Exception
{
    public function __construct(string $message = 'Route not found', int $code = 404)
    {
        parent::__construct($message, $code);
    }
}
