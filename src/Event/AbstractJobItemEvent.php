<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event;

use App\Entity\JobItem;

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
