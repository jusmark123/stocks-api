<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Brokerage;

use App\Constants\Brokerage\BrokerageConstants;
use App\Entity\Brokerage;
use App\Service\Brokerage\Interfaces\BrokerageServiceInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class BrokerageServiceAwareTrait.
 */
trait BrokerageServiceAwareTrait
{
    /** @var iterable */
    private $brokerageServices;

    /**
     * @param Brokerage $brokerage
     *
     * @return BrokerageServiceInterface
     */
    public function getBrokerageService(Brokerage $brokerage): BrokerageServiceInterface
    {
        $brokerageService = null;

        foreach ($this->brokerageServices as $brokerageService) {
            if ($brokerageService->supports($brokerage)) {
                break;
            }

            if (null === $this->brokerageService) {
                throw new NotFoundHttpException(BrokerageConstants::BROKERAGE_SERVICE_NOT_FOUND);
            }
        }

        return $brokerageService;
    }
}
