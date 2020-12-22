<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\Command;

use App\MessageClient\BunnyAsyncClient;
use App\MessageClient\ClientListener\ClientListenerProvider;
use App\MessageClient\Exception\QueueConfigurationException;
use Psr\Log\LoggerInterface;
use React\EventLoop\LoopInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ConsumerRun.
 */
class ConsumerRun extends Command
{
    const COMMAND_NAME = 'stocks-api:message-client:consumer:run';
    const IGNORE_CLIENT_ERROR = 'ignore-client-error';

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var LoopInterface
     */
    protected $loop;

    /**
     * @var ClientListenerProvider
     */
    protected $listenerProvider;

    /**
     * ConsumerRun constructor.
     *
     * @param LoopInterface          $loop
     * @param LoggerInterface        $logger
     * @param ClientListenerProvider $listenerProvider
     */
    public function __construct(
        LoopInterface $loop,
        LoggerInterface $logger,
        ClientListenerProvider $listenerProvider
    ) {
        $this->loop = $loop;
        $this->logger = $logger;
        $this->listenerProvider = $listenerProvider;
        parent::__construct(self::COMMAND_NAME);
    }

    protected function configure()
    {
        $this->addOption(
          static::IGNORE_CLIENT_ERROR,
          null,
          InputOption::VALUE_NONE,
          'ignore client errors'
        );
    }

    protected function onClientError(BunnyAsyncClient $client, \Throwable $e)
    {
        $this->logger->error('there was a client error', [
           'exception' => $e,
        ]);

        $client->disconnect();

        $this->loop->addTimer(2, function () {
            $this->death();
        });
    }

    protected function death()
    {
        echo 'something is borked...its all fucked... exiting...';
        exit(1);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logger->info('registering listeners');

        if (!$this->listenerProvider) {
            throw new QueueConfigurationException('the client listener is not configured');
        }

        $this->listenerProvider->registerListeners()->then(
            function ($client) use ($input) {
                $this->logger->info('listeners registered');
                if ($client instanceof BunnyAsyncClient && !$input->getOption(static::IGNORE_CLIENT_ERROR)) {
                    $client->on('error', function (\Throwable $e) use ($client) {
                        $this->onClientError($client, $e);
                    });
                }
            },
            function (\Throwable $e) {
                $this->logger->critical('Error occurred while registering listeners', [
                   'exception' => $e,
                ]);
                $this->loop->stop();
            }
        );

        $this->loop->run();

        return 0;
    }
}
