<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event;

/**
 * Class AbstractJobItemFailedEvent.
 */
abstract class AbstractJobItemFailedEvent extends AbstractJobFailedEvent
{
    public function __construct(\Exception $e, $jobItem)
    {
        $job = null === $jobItem ? null : $jobItem->getJob();

        parent::__construct($e, $job, $jobItem);
    }
}
