<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Command\Ticker;

use ApiPlatform\Core\Exception\ItemNotFoundException;
use App\Command\AbstractCommand;
use App\Constants\Transport\JobConstants;
use App\DataPersister\SyncTickersDataPersister;
use App\DTO\SyncTickersRequest;
use App\Entity\Account;
use App\Entity\Job;
use App\Entity\Source;
use App\Service\Brokerage\PolygonBrokerageService;
use App\Service\DefaultTypeService;
use App\Service\JobService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class SyncTickersCommand.
 */
class SyncTickersCommand extends AbstractCommand implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    const NAME = 'stocks-api:api:sync-tickers';
    const QUEUES = ['job', 'async', 'app'];

    /**
     * @var DefaultTypeService
     */
    private $defaultTypeService;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var JobService
     */
    private $jobService;

    /**
     * @var PolygonBrokerageService
     */
    private $polygonService;

    /**
     * SyncTickersCommand constructor.
     *
     * @param DefaultTypeService      $defaultTypeService
     * @param EntityManagerInterface  $entityManager
     * @param JobService              $jobService
     * @param LoggerInterface         $logger
     * @param MessageBusInterface     $messageBus
     * @param PolygonBrokerageService $polygonService
     */
    public function __construct(
        DefaultTypeService $defaultTypeService,
        EntityManagerInterface $entityManager,
        JobService $jobService,
        LoggerInterface $logger,
        MessageBusInterface $messageBus,
        PolygonBrokerageService $polygonService
    ) {
        parent::__construct(self::NAME);
        $this->defaultTypeService = $defaultTypeService;
        $this->entityManager = $entityManager;
        $this->jobService = $jobService;
        $this->logger = $logger;
        $this->messageBus = $messageBus;
        $this->polygonService = $polygonService;
    }

    protected function configure()
    {
        $this->setName(self::NAME)
            ->setDescription('Sync ticker from brokerage')
            ->addOption(
                'job_id',
                'j',
                InputOption::VALUE_OPTIONAL,
                'Specify a job uuid to restart a failed or cancelled job. Job must exist and cannot have been previously completed',
                null
            )
            ->addOption(
                'account_id',
                'a',
                InputOption::VALUE_OPTIONAL,
                'Specify an account uuid to use. Determines brokerage',
                null
            )
            ->addOption(
                'source_id',
                's',
                InputOption::VALUE_OPTIONAL,
                'Specify a source for attribution'
            )
            ->addOption(
                'parameters',
                'f',
                InputOption::VALUE_OPTIONAL,
                'Array of parameters to add to brokerage api call. Must follow brokerage api specifications',
                '{"market":"stocks","perpage":500,"sort":"ticker"}',
            )
            ->addOption(
                'limit',
                'l',
                InputOption::VALUE_OPTIONAL,
                'Limit number of processed tickers, mainly used for testing purposes'
            )
            ->addOption(
                'consume',
                'c',
                InputOption::VALUE_OPTIONAL,
                'Start consumers for message processing',
                false
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
            $job = $this->getJob($input->getOption('job_id'));
            $source = $this->getSource($input->getOption('source_id'));
            $account = $this->getAccount($input->getOption('account_id'));
            $parameters = $this->getParameters($input->getOption('parameters'));
            $limit = $input->getOption('limit');

            $request = new SyncTickersRequest($account, $source, $parameters, $limit, $job);
            $jobRequest = new SyncTickersDataPersister($this->messageBus, $this->defaultTypeService, $this->jobService);
            $job = $jobRequest->persist($request);

            $output->writeln(
                sprintf('Sync tickers job initiated. View progress via the /api/stocks/v1/jobs/%s',
                    $job->getGuid()->toString())
            );

            if ($input->getOption('consume')) {
                $this->startConsumers($output);
            }
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

    /**
     * @param string|null $jobId
     *
     * @return Job|null
     */
    private function getJob(?string $jobId = null): ?Job
    {
        $job = null;
        if (null !== $jobId) {
            $job = $this->entityManager
                ->getRepository(Job::class)
                ->findOneBy(['guid' => $jobId]);

            if (!$job instanceof Job) {
                throw new ItemNotFoundException(JobConstants::JOB_NOT_FOUND);
            }
        }

        return $job;
    }

    /**
     * @param string|null $accountId
     *
     * @return Account|null
     */
    private function getAccount(?string $accountId = null): ?Account
    {
        $account = null;
        if (null !== $accountId) {
            $account = $this->entityManager
                ->getRepository(Account::class)
                ->findOneBy(['guid' => $accountId]);
        }

        if (!$account instanceof Account) {
            $account = $this->polygonService->getDefaultAccount();
        }

        return $account;
    }

    /**
     * @param string|null $sourceId
     *
     * @return Source|null
     */
    private function getSource(?string $sourceId = null): ?Source
    {
        $source = null;
        if (null !== $sourceId) {
            $source = $this->entityManager
                ->getRepository(Source::class)
                ->findOneBy(['guid' => $sourceId]);
        }

        if (!$source instanceof Source) {
            $source = $this->defaultTypeService->getDefaultSource();
        }

        return $source;
    }

    /**
     * @param string|null $parameters
     *
     * @return array|null
     */
    private function getParameters(?string $parameters = null)
    {
        if (null === $parameters) {
            $parameters = [];
        } else {
            $parameters = json_decode($parameters, true);
        }

        if (!\is_array($parameters)) {
            $parameters = [$parameters];
        }

        return $parameters;
    }

    private function startConsumers(OutputInterface $output)
    {
        $command = $this->getApplication()->find('messenger:consume');
        $input = new ArrayInput(['receivers' => self::QUEUES]);

        return $command->run($input, new NullOutput());
    }

    private function stopConsumers(OutputInterface $output)
    {
        $command = $this->getApplication()->find('messenger:stop-workers');
        $input = new ArrayInput([]);

        return $command->run($input, $output);
    }
}
