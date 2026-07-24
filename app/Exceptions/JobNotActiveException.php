<?php

namespace App\Exceptions;

use Exception;

class JobNotActiveException extends Exception
{
    public function __construct(string $message = "Job is no longer active", ?\Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
