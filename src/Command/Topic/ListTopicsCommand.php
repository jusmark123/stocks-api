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
use Symfony\Component\Console\Output\OutputInterface;

class ListTopicsCommand extends AbstractCommand
{
    const NAME = 'stocks-api:topic:list-topics';

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
            ->setDescription('List available topics');
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
            $topics = $this->topicService->listTopics();

            if (!empty($topics)) {
                $rows = [];
                $output->writeln('Available Topics');
                foreach ($topics  as $topic) {
                    $rows[] = [$topic->getName(), $topic->getTopicArn(), $topic->getType()];
                }
                $table = (new Table($output))
                    ->setHeaders(['Name', 'TopicArn', 'Type'])
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
