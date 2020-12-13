<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Command;

use App\Service\Message\OrderInfoMessageService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class OrderInfoReceiverCommand.
 */
class OrderInfoReceiverCommand extends AbstractProcessCommand
{
    const COMMAND_NAME = 'stocks-api:api:order-info-receiver';

    /**
     * @var OrderInfoMessageService
     */
    private $orderInfoReceiverService;

    /**
     * OrderInfoReceiverCommand constructor.
     *
     * @param LoggerInterface         $logger
     * @param OrderInfoMessageService $orderInfoReceiverService
     */
    public function __construct(
        LoggerInterface $logger,
        OrderInfoMessageService $orderInfoReceiverService
    ) {
        $this->orderInfoReceiverService = $orderInfoReceiverService;
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

        echo json_encode($message);

        $this->orderInfoReceiverService->receive($message);

        return 0;
    }
}
