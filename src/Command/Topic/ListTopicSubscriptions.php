<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Command\Topic;

use App\Command\AbstractCommand;
use App\Service\TopicService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ListTopicSubscriptions extends AbstractCommand
{
    const NAME = 'stocks-api:topic:list-subscriptions';

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var TopicService
     */
    private $topicService;

    /**
     * TopicSubscribeCommand constructor.
     *
     * @param LoggerInterface $logger
     * @param TopicService    $topicService
     */
    public function __construct(LoggerInterface $logger, TopicService $topicService)
    {
        parent::__construct(self::NAME);
        $this->logger = $logger;
        $this->topicService = $topicService;
    }

    protected function configure()
    {
        $this->setName(self::NAME)
            ->setDescription('List topic subscriptions')
            ->addOption(
                'topic',
                't',
                InputOption::VALUE_OPTIONAL,
                'Retrieve subscriptions for a topic. Use list-topics command to get desired TopicArn',
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $topic = null;
            $topicArn = $input->getOption('topic');
            if (null !== $topicArn) {
                $topic = $this->topicService->getTopic($topicArn, true);
                $subscriptions = $topic->getSubscriptions();
            } else {
                $subscriptions = $this->topicService->listSubscriptions($topic);
            }

            if (!empty($subscriptions)) {
                $rows = [];
                $output->writeln('Subscriptions');
                foreach ($subscriptions as $subscription) {
                    $rows[] = [
                        $subscription->getTopic()->getName(),
                        $subscription->getSubscriptionArn() ?? 'null',
                        $subscription->getEndpoint(),
                        $subscription->isConfirmed() ? 'true' : 'false',
                    ];
                }
                $table = (new Table($output))
                    ->setHeaders(['Topic', 'SubscriptionArn', 'Endpoint', 'Confirmed'])
                    ->setRows($rows);
                $table->render();
            } else {
                $output->writeln('No Topics Found');
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), [
                'message' => $e->getMessage(),
                'class' => self::class,
                'line' => $e->getLine(),
            ]);

            return 1;
        }

        return 0;
    }
}
