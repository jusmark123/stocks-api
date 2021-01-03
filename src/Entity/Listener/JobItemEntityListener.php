<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Listener;

use App\Entity\JobItem;
use Predis\Client;

/**
 * Class JobItemEntityListener.
 */
class JobItemEntityListener extends AbstractEntityListener
{
    /**
     * @var Client
     */
    private $cache;

    /**
     * JobItemEntityListener constructor.
     *
     * @param Client $redis
     */
    public function __construct(Client $redis)
    {
        $this->cache = $redis;
    }

    /**
     * @param JobItem $jobItem
     */
    public function preUpdate(JobItem $jobItem)
    {
        $this->setStatusFromCache($jobItem);
    }

    /**
     * @param JobItem $jobItem
     */
    public function setStatusFromCache(JobItem $jobItem)
    {
        $status = $this->cache->get($jobItem->getGuid()->toString());
        $jobItem->setStatus($status ?? $jobItem->getStatus());
    }
}
