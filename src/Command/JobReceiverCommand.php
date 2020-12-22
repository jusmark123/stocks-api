<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Command;

use App\Service\Message\JobReceiverService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class JobReceiverCommand.
 */
class JobReceiverCommand extends AbstractProcessCommand
{
    const COMMAND_NAME = 'stocks-api:api:job-receiver';

    /**
     * @var JobReceiverService
     */
    private $jobReceiverService;

    /**
     * JobReceiverCommand constructor.
     *
     * @param LoggerInterface    $logger
     * @param JobReceiverService $jobReceiverService
     */
    public function __construct(
        LoggerInterface $logger,
        JobReceiverService $jobReceiverService
    ) {
        $this->jobReceiverService = $jobReceiverService;
        parent::__construct($logger);
    }

    /**
     * @return mixed|string
     */
    protected static function getCommandName()
    {
        return static::COMMAND_NAME;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws \Exception
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $message = $this->fetchStdInJson($input);

        $this->logger->info('processing order info', [
            'order_id' => $message['id'],
        ]);

        $this->logger->debug("'I'm here......");

        $this->jobReceiverService->receive($message);

        return 0;
    }
}
