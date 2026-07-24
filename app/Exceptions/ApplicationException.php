<?php

namespace App\Exceptions;

use Exception;

class ApplicationException extends Exception
{
    protected $statusCode;

    public function __construct(string $message = "Application error occurred", int $statusCode = 400, ?\Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
