<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service;

use App\DTO\SyncPositionsRequest;
use App\Entity\Job;
use App\Entity\Position;
use App\Helper\ValidationHelper;
use App\Service\Brokerage\BrokerageServiceProvider;
use Psr\Log\LoggerInterface;

/**
 * Class PositionService.
 */
class PositionService extends AbstractService
{
    private $brokerageServiceProvider;

    private $defaultTypeService;

    private $positionEntityService;

    private $validator;

    public function __construct(
        BrokerageServiceProvider $brokerageServiceProvider,
        DefaultTypeService $defaultTypeService,
        LoggerInterface $logger,
        PositionEntityService $positionEntityService,
        ValidationHelper $validator
    ) {
        $this->brokerageServiceProvider = $brokerageServiceProvider;
        $this->defaultTypeService = $defaultTypeService;
        $this->logger = $logger;
        $this->positionEntityService = $positionEntityService;
        $this->validator = $validator;
        parent::__construct($logger);
    }

    /**
     * @param SyncPositionHistory $request
     * @param Job                 $job
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function fetchPositionHistory(SyncPositionsRequest $request, Job $job): ?Job
    {
        try {
            $brokerageService = $this->brokerageServiceProvider
                ->getBrokerageService($request->getAccount()->getBrokerage());

            return $brokerageService->fetchPositionHistory($request, $job);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param array $portfolioHistoryMessage
     * @param Job   $job
     *
     * @return Position|null
     */
    public function syncPositionHistory(array $portfolioHistoryMessage, Job $job): ?Position
    {
        $account = $job->getAccount();
        $source = $job->getSource();

        $brokerageService = $this->brokerageServiceProvider->getBrokerageService($account->getBrokerage());

        $positionHistory = $brokerageService
            ->createPositionHistoryFromMessage($portfolioHistoryMessage)
            ->setAccount($account)
            ->setSource($source);

        if (null === $positionHistory->getUser()) {
            $positionHistory->setUser($this->defaultTypeService->getDefaultUser());
        }

        $position = $brokerageService->createPositionFromPositionHistory($positionHistory, $job);
        $this->positionEntityService->save($position);

        return $position;
    }
}
