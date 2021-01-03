<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class JobRepository extends EntityRepository
{
    public function getJobStatus($jobId)
    {
        $qb = $this->createQueryBuilder('job');

        $qb->select('status')
            ->where('job.guid = :jobId')
            ->setParameter('jobId', $jobId);

        return $qb->getQuery()->getResult();
    }
}
