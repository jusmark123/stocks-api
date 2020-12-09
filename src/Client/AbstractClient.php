<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Client;

use Http\Client\HttpClient;
use Http\Message\RequestFactory;
use Http\Message\UriFactory;
use Psr\Log\LoggerInterface;

abstract class AbstractClient implements BrokerageClientInterface
{
    /**
     * @var HttpClient
     */
    protected $client;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var RequestFactory
     */
    protected $requestFactory;

    /**
     * @var UriFactory
     */
    protected $uriFactory;

    /**
     * AbstractClient Constructor.
     *
     * @param HttpClient      $client
     * @param LoggerInterface $logger
     * @param RequestFactory  $requestFactory
     * @param UriFactory      $uriFactory
     */
    public function __construct(
        HttpClient $client,
        LoggerInterface $logger,
        RequestFactory $requestFactory,
        UriFactory $uriFactory
    ) {
        $this->client = $client;
        $this->logger = $logger;
        $this->requestFactory = $requestFactory;
        $this->uriFactory = $uriFactory;
    }
}
