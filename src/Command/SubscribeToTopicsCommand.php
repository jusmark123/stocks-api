<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Command;

use ApiPlatform\Core\Exception\ItemNotFoundException;
use App\Entity\Topic;
use App\Service\TopicService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SubscribeToTopicsCommand.
 */
class SubscribeToTopicsCommand extends Command
{
    const NAME = 'stocks-api:api:subscribe-to-topics';

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var TopicService
     */
    private $topicService;

    /**
     * SubscribeToTopicsCommand constructor.
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
            ->setDescription('Subscribe to available topics')
            ->addOption(
              'topics',
              't',
              InputOption::VALUE_OPTIONAL,
              'List of topic names to subscribe',
              ''
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $topics = json_decode($input->getOption('topics'), true);

            if (!empty($topics)) {
                $topicEntityManager = $this->topicService->getTopicEntityManager();
                foreach ($topics as $key => $topicName) {
                    $topic = $topicEntityManager->findOneBy(['name' => $topicName]);
                    if (!$topic instanceof Topic) {
                        throw new ItemNotFoundException($topicEntityManager::TOPIC_NOT_FOUND);
                    }
                    $topics[$key] = $topic;
                }
            } else {
                $this->topicService->syncTopics();
            }

            $this->topicService->syncSubscriptions();
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
