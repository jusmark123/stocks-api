<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service;

use ApiPlatform\Core\Bridge\Symfony\Validator\Validator;
use App\DTO\SyncPositionsRequest;
use App\Entity\Job;
use App\Entity\Position;
use App\Helper\ValidationHelper;
use App\Service\Brokerage\BrokerageServiceProvider;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class PositionService.
 */
class PositionService extends AbstractService
{
    private BrokerageServiceProvider $brokerageServiceProvider;

    private DefaultTypeService $defaultTypeService;

    private EntityManagerInterface $entityManager;

    private ValidationHelper $validator;

    public function __construct(
        BrokerageServiceProvider $brokerageServiceProvider,
        DefaultTypeService $defaultTypeService,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        ValidationHelper $validator
    ) {
        $this->brokerageServiceProvider = $brokerageServiceProvider;
        $this->defaultTypeService = $defaultTypeService;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
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
