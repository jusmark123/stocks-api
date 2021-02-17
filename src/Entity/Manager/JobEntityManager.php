<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Manager;

use App\Entity\Job;

/**
 * Class JobEntityManager.
 */
class JobEntityManager extends EntityManager
{
    const ENTITY_CLASS = Job::class;
}
