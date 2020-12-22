<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Brokerage;

use App\Entity\Account;
use App\Helper\ValidationHelper;
use App\MessageClient\ClientPublisher\ClientPublisher;
use App\MessageClient\Protocol\MessageFactory;
use App\Service\JobService;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

abstract class AbstractBrokerageService implements BrokerageServiceInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var JobService
     */
    protected $jobService;

    /**
     * @var ValidationHelper
     */
    protected $validator;

    /**
     * AbstractBrokerageService constructor.
     *
     * @param JobService       $jobService
     * @param LoggerInterface  $logger
     * @param MessageFactory   $messageFactory
     * @param ClientPublisher  $publisher
     * @param ValidationHelper $validator
     */
    public function __construct(
        JobService $jobService,
        LoggerInterface $logger,
        ValidationHelper $validator
    ) {
        $this->jobService = $jobService;
        $this->logger = $logger;
        $this->validator = $validator;
    }

    /**
     * @param string       $uri
     * @param Account|null $account
     * @param array|null   $params
     *
     * @return string
     */
    protected function getUri(string $uri, ?Account $account = null, ?array $params = null): string
    {
        $uri = $account->getApiEndpointUrl().$uri;

        if (null !== $params) {
            $uri .= '&'.http_build_query($params);
        }

        return $uri;
    }
}
