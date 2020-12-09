<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Brokerage;

use App\Entity\Account;
use App\Helper\ValidationHelper;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

abstract class AbstractBrokerageService implements BrokerageServiceInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var ValidationHelper
     */
    protected $validator;

    /**
     * AbstractBrokerageService constructor.
     *
     * @param LoggerInterface  $logger
     * @param ValidationHelper $validator
     */
    public function __construct(
        LoggerInterface $logger,
        ValidationHelper $validator
    ) {
        $this->logger = $logger;
        $this->validator = $validator;
    }

    /**
     * @param string       $uri
     * @param Account|null $account
     *
     * @return string
     */
    protected function getUri(string $uri, ?Account $account = null): string
    {
        return $account->getapiEndpointUrl().$uri;
    }
}
