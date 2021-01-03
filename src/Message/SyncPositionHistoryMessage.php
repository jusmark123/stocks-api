<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message;

use App\Message\Job\AbstractJobRequestMessage;

class SyncPositionHistoryMessage extends AbstractJobRequestMessage
{
    private $portfolioService;
}
