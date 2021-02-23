<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Listener;

use App\Entity\User;
use App\Service\DefaultTypeService;
use Doctrine\ORM\EntityManagerInterface;

class UserEntityListener extends AbstractEntityListener
{
    private EntityManagerInterface $entityManager;
    private DefaultTypeService $defaultTypeService;

    public function __construct(
        DefaultTypeService $defaultTypeService,
        EntityManagerInterface $entityManager
    ) {
        $this->defaultTypeService = $defaultTypeService;
        $this->entityManager = $entityManager;
    }

    public function prePersist(User $user)
    {
        $this->defaultTypeService->setSource($user);
    }

    private function setSource(User $user)
    {
    }
}
