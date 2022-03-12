<?php

namespace App\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;

class FailResponse extends Exception
{
    #[Pure] public function __construct(string $message = "", int $code = 422, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
