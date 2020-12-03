<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient;

use Bunny\Async\Client;
use Bunny\Exception\ClientException;
use Evenement\EventEmitterInterface;
use Evenement\EventEmitterTrait;
use React\Promise;

/**
 * Class BunnyAsyncClient.
 */
class BunnyAsyncClient extends Client implements EventEmitterInterface
{
    use EventEmitterTrait;

    public function onDataAvailable()
    {
        try {
            parent::onDataAvailable();
        } catch (\Throwable $e) {
            $this->eventLoop->removeReadStream($this->getStream());
            $this->emitFuture('error', [$e]);
        }
    }

    /**
     * @return Promise\PromiseInterface
     */
    public function connect()
    {
        $deferred = new Promise\Deferred();

        $errBack = function (\Throwable $e) use ($deferred, &$errBack) {
            $this->removeListener('error', $errBack);
            $deferred->reject($e);
        };

        $this->on('error', $errBack);

        parent::connect()->then(
            function () use ($deferred) {
                return $deferred->resolve($this);
            },
            function (\Throwable $e) use ($deferred) {
                $deferred->reject($e);
            }
        )->always(function () use ($errBack) {
            $this->removeListener('error', $errBack);
        });

        return $deferred->promise();
    }

    /**
     * @param int    $replyCode
     * @param string $replyText
     *
     * @return Promise\PromiseInterface
     */
    public function disconnect($replyCode = 0, $replyText = '')
    {
        return parent::disconnect(
            $replyCode,
            $replyText
        )->always(function () use ($replyCode, $replyText) {
            $this->emit('disconnect', [
                $replyCode,
                $replyText,
                $this,
            ]);
        });
    }

    /**
     * @param string $event
     * @param array  $args
     */
    public function emitFuture(string $event, array $args = [])
    {
        $this->eventLoop->futureTick(function () use ($event, $args) {
            $this->emit($event, $args + [$this]);
        });
    }

    public function read()
    {
        try {
            parent::read();
        } catch (ClientException $clientException) {
            $this->eventLoop->removeReadStream($this->getStream());
            $this->emitFuture('error', [$clientException]);
            $this->disconnect();
        }
    }

    public function write()
    {
        try {
            parent::write();
        } catch (ClientException $clientException) {
            $this->eventLoop->removeWriteStream($this->getStream());
            $this->emitFuture('error', [$clientException]);
            $this->disconnect();
        }
    }
}
