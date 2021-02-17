<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Exception;

use Throwable;

/**
 * Class HttpMessageException.
 */
class HttpMessageException extends \Exception
{
    /**
     * HttpMessageException constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
