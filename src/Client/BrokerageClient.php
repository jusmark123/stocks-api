<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Client;

use App\Entity\Account;
use App\Entity\Brokerage;
use App\Exception\HttpMessageException;
use Http\Client\HttpClient;
use Http\Message\RequestFactory;
use Http\Message\UriFactory;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * Class BrokerageClient.
 */
class BrokerageClient extends AbstractClient implements BrokerageClientInterface
{
    /**
     * @var Account
     */
    private Account $account;

    /**
     * @var Brokerage
     */
    private Brokerage $brokerage;

    /**
     * BrokerageClient Constructor.
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
        parent::__construct(
            $client,
            $logger,
            $requestFactory,
            $uriFactory);
    }

    /**
     * @param Brokerage $brokerage
     *
     * @return bool
     */
    public function supports(Brokerage $brokerage): bool
    {
        return true;
    }

    /**
     * @return Account
     */
    public function getAccount(): Account
    {
        return $this->account;
    }

    /**
     * @param Account $account
     *
     * @return BrokerageClient
     */
    public function setAccount(Account $account): BrokerageClient
    {
        $this->account = $account;
        $this->setBrokerage($account->getBrokerage());

        return $this;
    }

    /**
     * @param Brokerage $brokerage
     *
     * @return BrokerageClient
     */
    public function setBrokerage(Brokerage $brokerage): BrokerageClient
    {
        $this->brokerage = $brokerage;

        return $this;
    }

    /**
     * @return Brokerage
     */
    public function getBrokerage(): Brokerage
    {
        return $this->brokerage;
    }

    /**
     * @param RequestInterface $request
     *
     * @throws ClientExceptionInterface|HttpMessageException
     *
     * @return ResponseInterface
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $response = $this->client->sendRequest($request);

        return $this->validateResponse($response);
    }

    /**
     * @param string $url
     * @param string $method
     * @param array  $headers
     * @param array  $body
     *
     * @return RequestInterface
     */
    public function createRequest(string $url, string $method = 'GET', array $headers = [], array $body = []): RequestInterface
    {
        if ('POST' === $method) {
            $headers['Content-Length'] = \strlen(json_encode($body));
            $headers['Content-Type'] = 'application/json';
        }

        return $this->requestFactory->createRequest(
            $method,
            $url,
            $headers,
            \in_array($method, ['POST', 'PUT'], true) ? json_encode($body) : ''
        );
    }

    /**
     * @param ResponseInterface $response
     *
     * @throws HttpMessageException
     *
     * @return ResponseInterface
     */
    private function validateResponse(ResponseInterface $response): ?ResponseInterface
    {
        if ($response && HttpResponse::HTTP_OK === $response->getStatusCode()) {
            return $response;
        }

        $this->logger->debug(
            'Error response return from brokerage client api request',
            [
                'response' => [
                    'status' => $response->getStatusCode(),
                    'reason' => $response->getReasonPhrase(),
                    'headers' => $response->getHeaders(),
                    'body' => (string) $response->getBody(),
                ],
            ]
        );

        throw new HttpMessageException($response->getReasonPhrase(), $response->getStatusCode());
    }
}
