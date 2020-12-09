<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Command;

use App\Entity\Manager\TickerEntityManager;
use App\Service\Brokerage\PolygonService;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SyncTickersCommand.
 */
class SyncTickersCommand extends Command implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    const NAME = 'stocks-api:api:sync-tickers';

    /**
     * @var PolygonService
     */
    private $polygonService;

    /**
     * @var TickerEntityManager
     */
    private $tickerManager;

    public function __construct(
        LoggerInterface $logger,
        PolygonService $polygonService,
        TickerEntityManager $tickerEntityManager
    ) {
        $this->polygonService = $polygonService;
        $this->tickerManager = $tickerEntityManager;

        $this->setLogger($logger);
        parent::__construct(self::NAME);
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
            $this->polygonService->syncTickers();
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
