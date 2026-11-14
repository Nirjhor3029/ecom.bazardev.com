<?php

namespace App\Exceptions;

use Exception;

/** A business-rule violation with a specific SRS error code and HTTP status. */
class ApiException extends Exception
{
    public function __construct(
        public readonly string $errorCode,
        string $message,
        public readonly int $status = 409,
        public readonly array $details = [],
    ) {
        parent::__construct($message);
    }
}
