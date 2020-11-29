<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Brokerage;

use App\Entity\Account;
use App\Service\AbstractService;
use App\Service\Brokerage\Interfaces\BrokerageServiceInterface;

abstract class AbstractBrokerageService extends AbstractService implements BrokerageServiceInterface
{
    /**
     * @param Account $account
     * @param string  $uri
     *
     * @return string
     */
    protected function getUri(Account $account, string $uri): string
    {
        return $account->getapiEndpointUrl().$uri;
    }
}
