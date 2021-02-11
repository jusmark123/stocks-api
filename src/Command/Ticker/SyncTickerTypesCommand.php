<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Command\Ticker;

use App\Entity\Manager\TickerTypeEntityManager;
use App\Service\TickerService;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SyncTickerTypesCommand.
 */
class SyncTickerTypesCommand extends Command implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    const NAME = 'stocks-api:api:sync-ticker-types';

    /**
     * @var TickerService
     */
    private $tickerService;

    /**
     * @var TickerTypeEntityManager
     */
    private $entityManager;

    public function __construct(
        TickerTypeEntityManager $entityManager,
        LoggerInterface $logger,
        TickerService $tickerService
    ) {
        parent::__construct(self::NAME);
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->tickerService = $tickerService;
    }

    protected function configure()
    {
        $this->setName(self::NAME)
            ->setDescription('Sync Tickers and TickerTypes using Polygon IO api')
            ->addOption(
                'type',
                't',
                InputOption::VALUE_OPTIONAL,
                'Type of tickers to query',
                ''
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $output->writeln('Syncing TickerTypes....');
            $this->tickerService->syncTickerTypes();
            $count = \count($this->entityManager->findAll());
            $output->writeln(sprintf('Synced %d TickerTypes to database', $count));
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), [
               'code' => $e->getCode(),
               'message' => $e->getMessage(),
               'class' => self::class,
            ]);

            $output->writeln(sprintf("\nError occurred: %s", $e->getMessage()));

            return 1;
        }

        return 0;
    }
}
