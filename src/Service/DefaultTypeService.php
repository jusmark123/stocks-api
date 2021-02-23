<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service;

use App\Constants\Entity\UserConstants;
use App\Entity\Source;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class DefaultTypeService extends AbstractService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        parent::__construct($logger);
    }

    /**
     * @return object|Source
     */
    public function getDefaultSource(): object
    {
        return $this->entityManager
            ->getRepository(Source::class)
            ->find(1);
    }

    /**
     * @return object|User
     */
    public function getDefaultUser()
    {
        return $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['username' => UserConstants::SYSTEM_USER_USERNAME]);
    }
}
