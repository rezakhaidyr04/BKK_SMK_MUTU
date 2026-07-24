<?php

namespace App\Exceptions;

use Exception;

class JobNotFoundException extends Exception
{
    public function __construct(string $message = "Job not found or no longer available", ?\Throwable $previous = null)
    {
        parent::__construct($message, 404, $previous);
    }
}
