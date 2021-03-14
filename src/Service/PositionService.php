<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service;

use App\DTO\SyncPositionsRequest;
use App\Entity\Account;
use App\Entity\Factory\PositionFactory;
use App\Entity\Job;
use App\Entity\Order;
use App\Entity\Position;
use App\Helper\ValidationHelper;
use App\Service\Brokerage\BrokerageServiceProvider;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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

    /**
     * PositionService constructor.
     *
     * @param BrokerageServiceProvider $brokerageServiceProvider
     * @param DefaultTypeService       $defaultTypeService
     * @param EntityManagerInterface   $entityManager
     * @param LoggerInterface          $logger
     * @param ValidationHelper         $validator
     */
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
     * @param Order $order
     */
    public function getPositionForOrder(Order $order)
    {
        $position = PositionFactory::createFromOrder($order);
        $order->setPosition($position);
    }

    /**
     * @param string  $symbol
     * @param Account $account
     *
     * @return Position|object
     */
    public function getPosition(string $symbol, Account $account): Position
    {
        return $this->entityManager
            ->getRepository(Position::class)
            ->findOneBy(['ticker' => $symbol, 'account' => $account]);
    }

    /**
     * @param Account $account
     *
     * @return array
     */
    public function getPositions(Account $account): array
    {
        $brokerageService = $this->brokerageServiceProvider->getBrokerageService($account->getBrokerage());

        return $brokerageService->fetchPositions($account);
    }

    /**
     * @param SyncPositionsRequest $request
     * @param Job                  $job
     *
     * @throws Exception
     *
     * @return mixed
     */
    public function fetchPositionHistory(SyncPositionsRequest $request, Job $job): ?Job
    {
        try {
            return null;
        } catch (Exception $e) {
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
