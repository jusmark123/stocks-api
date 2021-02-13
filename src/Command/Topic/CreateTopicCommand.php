<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Command\Topic;

use ApiPlatform\Core\Exception\InvalidArgumentException;
use App\Command\AbstractCommand;
use App\Entity\Factory\TopicFactory;
use App\Service\TopicService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateTopicCommand extends AbstractCommand
{
    const NAME = 'stocks-api:topic:create';

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
            ->setDescription('Create topic')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Name of SNS topic. Required usage with --create-topic',
            )
            ->addArgument(
                'type',
                InputArgument::OPTIONAL,
                'Type of SNS topic valid values are `fifo` or `standard`.',
                'standard'
            )
            ->addOption(
                'content-deduplication',
                'd',
                InputOption::VALUE_OPTIONAL,
                'If true, Amazon SNS uses a SHA-256 hash to generate the MessageDeduplicationId using the body of the message (but not the attributes of the message). (Optional) To override the generated value, you can specify a value for the the MessageDeduplicationId parameter for the Publish action.',
                false
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $attributes = [];
            $deduplication = $input->getOption('content-deduplication');
            $name = $input->getArgument('name');
            $type = $input->getArgument('type');

            if (null === $name) {
                throw new InvalidArgumentException('Name is required when creating a topic');
            }

            $topicName = $name;
            $attributes['DisplayName'] = ucwords(str_replace(['-', '_'], ' ', $name));

            if ('fifo' === $type) {
                $attributes['FifoTopic'] = 'true';
                $attributes['ContentBasedDeduplication'] = $deduplication ? 'true' : 'false';
                if (false === strpos($topicName, '.fifo')) {
                    $topicName .= '.fifo';
                } else {
                    $name = str_replace('.fifo', '', $name);
                }
            }

            $topic = TopicFactory::create()
                ->setName(strtolower($topicName))
                ->setAttributes($attributes);

            $topic = $this->topicService->createTopic($topic);

            $output->writeln(sprintf('Topic %s created with TopicArn: %s', $topic->getName(), $topic->getTopicArn()));
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
