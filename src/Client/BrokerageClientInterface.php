<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Client;

use App\Entity\Brokerage;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use React\Promise\ExtendedPromiseInterface;

/**
 * Interface BrokerageClientInterface.
 */
interface BrokerageClientInterface
{
    /**
     * @param Brokerage $brokerage
     *
     * @return bool
     */
    public function supports(Brokerage $brokerage): bool;

    /**
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     */
    public function sendRequest(RequestInterface $request): ResponseInterface;

    /**
     * @param RequestInterface $request
     *
     * @return ExtendedPromiseInterface
     */
    public function sendAsyncRequest(RequestInterface $request): ExtendedPromiseInterface;
}
