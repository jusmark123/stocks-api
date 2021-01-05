<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use Psr\Log\LoggerInterface;

/**
 * Class UserService.
 */
class UserService extends AbstractService
{
    private $defaultTypeService;

    /**
     * UserService constructor.
     *
     * @param LoggerInterface    $logger
     * @param DefaultTypeService $defaultTypeService
     */
    public function __construct(LoggerInterface $logger, DefaultTypeService $defaultTypeService)
    {
        $this->defaultTypeService = $defaultTypeService;
        parent::__construct($logger);
    }

    /**
     * @return User
     */
    public function getCurrentUser(): User
    {
        return $this->defaultTypeService->getDefaultUser();
    }
}
