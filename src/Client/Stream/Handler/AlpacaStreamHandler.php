<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Client\Stream\Handler;

use App\Client\Stream\AbstractStreamHandler;
use App\Client\Stream\Protocol\Stream;
use App\Client\Stream\Protocol\StreamPacket;
use App\Constants\Brokerage\AlpacaConstants;
use App\Entity\Account;
use App\Entity\Brokerage;
use WebSocket\ISocketHandler;

class AlpacaStreamHandler extends AbstractStreamHandler implements ISocketHandler
{
    private Account $account;

    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    public function supports(Account $account): bool
    {
        return $account->getBrokerage() instanceof Brokerage
            && AlpacaConstants::BROKERAGE_NAME === $account->getBrokerage()->getName();
    }

    public function connect(Stream $stream)
    {
        // TODO: Implement connect() method.
    }

    public function consume(StreamPacket $packet, Stream $stream)
    {
        // TODO: Implement consume() method.
    }

    public function getSubscribedStreams()
    {
        // TODO: Implement getSubscribedStreams() method.
    }

    public function onConnect(&$client)
    {
        // TODO: Implement onConnect() method.
    }

    public function onReceive(&$client, $msg)
    {
        // TODO: Implement onReceive() method.
    }

    public function onError(&$client, $errno, $errmsg)
    {
        // TODO: Implement onError() method.
    }
}
