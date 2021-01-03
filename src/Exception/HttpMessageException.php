<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Exception;

use Throwable;

class HttpMessageException extends \Exception
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}