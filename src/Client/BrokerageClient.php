<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Client;

use App\Client\Interfaces\BrokerageClientInterface;
use App\Entity\Account;
use App\Entity\Brokerage;
use Http\Client\HttpClient;
use Http\Message\RequestFactory;
use Http\Message\UriFactory;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class BrokerageClient extends AbstractClient implements BrokerageClientInterface
{
    /**
     * @var Account
     */
    private $account;

    /**
     * @var Brokerage
     */
    private $brokerage;

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
    public function setAccount(Account $account)
    {
        $this->account = $account;
        $this->setBrokerage($account->getBrokerage());
    }

    /**
     * @param Brokerage $brokerage
     *
     * @return BrokerageClient
     */
    public function setBrokerage(Brokerage $brokerage)
    {
        $this->brokerage = $brokerage;
    }

    /**
     * @return Brokerage
     */
    public function getBrokerage(): Brokerage
    {
        return $this->brokerage;
    }

    /**
     * @return RequestInterface
     */
    public function createAccountInfoRequest(): RequestInterface
    {
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }

    /**
     * @param RequestInterface $request
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     *
     * @return ResponseInterface|null
     */
    public function sendRequest(RequestInterface $request)
    {
        $response = $this->client->sendRequest($request);

        return $this->validateResponse($response);
    }

    /**
     * @param RequestInterface $request
     *
     * @return Promise
     */
    public function asyncRequest(RequestInterface $request)
    {
        return $this->client->asyncRequest($request);
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
            $headers['Content-Length'] = \strlen($body);
            $headers['Content-Type'] = 'application/json';
        }

        return $this->requestFactory->createRequest(
            $method,
            $url,
            $headers,
            \in_array($method, ['POST', 'PUT'], true) ? $body : ''
        );
    }

    /**
     * @param ResponseInterface $response
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
            ],
        );

        throw new \Exception(BrokerageClient::class, $response->getStatusCode());
    }
}
