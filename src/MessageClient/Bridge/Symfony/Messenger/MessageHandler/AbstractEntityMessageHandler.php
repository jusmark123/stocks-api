<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\Bridge\Symfony\Messenger\MessageHandler;

/**
 * Class AbstractEntityMessageHandler.
 */
abstract class AbstractEntityMessageHandler
{
    use EntityMessageHandlerTrait;
}
