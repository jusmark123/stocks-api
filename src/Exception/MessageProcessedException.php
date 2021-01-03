<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Exception;

use Throwable;

/**
 * Class MessageProcessedException.
 */
class MessageProcessedException extends \Exception
{
    const MESSAGE = 'Message previously processed';

    /**
     * MessageProcessedException constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        $message = empty($message) ? self::MESSAGE : $message;

        parent::__construct($message, $code, $previous);
    }
}
