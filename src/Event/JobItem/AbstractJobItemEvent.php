<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\JobItem;

use App\Entity\JobItem;
use App\Event\Job\AbstractJobEvent;

/**
 * Class AbstractJobItemEvent.
 */
abstract class AbstractJobItemEvent extends AbstractJobEvent
{
    /**
     * AbstractJobItemEvent constructor.
     *
     * @param JobItem $jobItem
     */
    public function __construct(JobItem $jobItem)
    {
        parent::__construct($jobItem->getJob(), $jobItem);
    }
}
