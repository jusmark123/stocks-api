<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\Bridge\Symfony\Messenger\MessageHandler;

use App\Entity\Interfaces\EntityInterface;
use App\MessageClient\Bridge\Symfony\Messenger\Message\EntityMessageAwareInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Interface EntityMessageHandlerInterface.
 */
interface EntityMessageHandlerInterface
{
    /**
     * @return ManagerRegistry
     */
    public function getManagerRegistry(): ManagerRegistry;

    /**
     * @param ManagerRegistry $managerRegistry
     *
     * @return EntityMessageHandlerInterface
     */
    public function setManagerRegistry(ManagerRegistry $managerRegistry): EntityMessageHandlerInterface;

    /**
     * @param EntityMessageAwareInterface $entityMessage
     * @param array                       $criteria
     *
     * @return EntityInterface|null
     */
    public function findEntity(EntityMessageAwareInterface $entityMessage, $criteria = []): ?EntityInterface;
}
