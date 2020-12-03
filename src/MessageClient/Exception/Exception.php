<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\Exception;

use Throwable;

class Exception extends \Exception
{
    const MESSAGE = 'Exception occurred';
    const PREFIX = '';

    public function __construct($message = '', int $code = 0, Throwable $previous = null)
    {
        if ($message instanceof Throwable) {
            $previous = $message;
            $message = '';
        }

        if (empty($message)) {
            $message = static::MESSAGE;
            if (empty($message) && $preious instanceof Throwable) {
                $message = $previous->getMEssage();
            }

            $message = static::PREFIX.$message;

            parent::__construct($message, $code, $previous);
        }
    }
}
