<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service;

use App\Constants\Entity\UserConstants;
use App\Entity\Manager\OrderStatusTypeEntityManager;
use App\Entity\PositionSideType;
use App\Entity\Source;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class DefaultTypeService extends AbstractService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var OrderStatusTypeEntityManager
     */
    private $orderStatusType;

    public function __construct(
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        OrderStatusTypeEntityManager $orderStatusType
    ) {
        $this->entityManager = $entityManager;
        $this->orderStatusType = $orderStatusType;
        parent::__construct($logger);
    }

    public function getDefaultPositionSideType(): PositionSideType
    {
    }

    /**
     * @return Source
     */
    public function getDefaultSource(): Source
    {
        return $this->entityManager
            ->getRepository(Source::class)
            ->find(1);
    }

    /**
     * @return User
     */
    public function getDefaultUser(): User
    {
        return $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['username' => UserConstants::SYSTEM_USER_USERNAME]);
    }
}
