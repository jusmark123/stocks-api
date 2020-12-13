<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Command;

use App\Constants\Transport\JobConstants;
use App\Entity\Factory\JobFactory;
use App\Entity\Manager\TickerEntityManager;
use App\Entity\User;
use App\Service\Brokerage\PolygonBrokerageService;
use Doctrine\ORM\EntityManagerInterface;
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
     * @var PolygonBrokerageService
     */
    private $polygonService;

    /**
     * @var TickerEntityManager
     */
    private $tickerManager;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * SyncTickersCommand constructor.
     *
     * @param EntityManagerInterface  $entityManager
     * @param LoggerInterface         $logger
     * @param PolygonBrokerageService $polygonService
     * @param TickerEntityManager     $tickerEntityManager
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        PolygonBrokerageService $polygonService,
        TickerEntityManager $tickerEntityManager
    ) {
        $this->entityManager = $entityManager;
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

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['name' => 'system-user']);
            $job = JobFactory::create()
                ->setName('Sync Tickers')
                ->setDescription($this->getDescription())
                ->setData([])
                ->setStatus(JobConstants::JOB_INITIATED)
                ->setUser($user);

            $this->entityManager->persist($job);
            $this->entityManager->flush();

            $this->polygonService->syncTickers($job);
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
