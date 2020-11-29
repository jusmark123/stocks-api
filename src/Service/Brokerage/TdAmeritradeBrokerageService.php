<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Brokerage;

use App\Constants\Brokerage\TdAmeritradeConstants;
use App\Entity\Account;
use App\Entity\Brokerage;
use App\Entity\Interfaces\AccountInfoInterface;

class TdAmeritradeBrokerageService extends AbstractBrokerageService
{
    /**
     * @param Brokerage $brokerage
     *
     * @return bool
     */
    public function supports(Brokerage $brokerage): bool
    {
        return $brokerage instanceof Brokerage && TdAmeritradeConstants::BROKERAGE_NAME === $brokerage->name;
    }

    /**
     * [getAccountInfo description].
     *
     * @param Account $account
     *
     * @return AccountInfoInterface
     */
    public function getAccountInfo(Account $account): AccountInfoInterface
    {
        $data = $this->brokerageClient->createRequest(
             $this->getUrl($account, TdAmeritradeConstants::ACCOUNT_ENDPOINT),
             $this->getRequestHeaders()
            )->then(
                     function (ResponseInterface $response) {
                         $this->brokerageClient->validateResponse($response);

                         return $response->getBody();
                     }
            );

        $accountInfo = $this->serializer->deserialize($data,
                TdAmeritradeConstants::ACCOUNT_INFO_ENTITY_CLASS,
                TdAmeritradeConstants::REQUEST_RETURN_DATA_TYPE, [
                        AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => true,
                    ]);
    }
}
