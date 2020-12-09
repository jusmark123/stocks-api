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
class JobEntityManager extends AbstractEntityManager
{
    const ENTITY_CLASS = Job::class;
}
