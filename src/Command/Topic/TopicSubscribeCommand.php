<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Command\Topic;

use App\Command\AbstractCommand;
use App\DTO\Aws\Sns\SubscriptionRequest;
use App\Service\TopicService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TopicSubscribeCommand.
 */
class TopicSubscribeCommand extends AbstractCommand
{
    const NAME = 'stocks-api:topic:subscribe';

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
            ->setDescription('Subscribe to available topics')
            ->addArgument(
                'topic',
                InputArgument::REQUIRED,
                'The ARN of the topic you want to subscribe to.'
            )
            ->addArgument(
                'protocol',
                InputArgument::REQUIRED,
                'The protocol that you want to use. Supported protocols include:

http – delivery of JSON-encoded message via HTTP POST

https – delivery of JSON-encoded message via HTTPS POST

email – delivery of message via SMTP

email-json – delivery of JSON-encoded message via SMTP

sms – delivery of message via SMS

sqs – delivery of JSON-encoded message to an Amazon SQS queue

application – delivery of JSON-encoded message to an EndpointArn for a mobile app and device

lambda – delivery of JSON-encoded message to an AWS Lambda function

firehose – delivery of JSON-encoded message to an Amazon Kinesis Data Firehose delivery stream.'
            )
            ->addArgument(
                'endpoint',
                InputArgument::REQUIRED,
                'The endpoint that you want to receive notifications. Endpoints vary by protocol:

For the http protocol, the (public) endpoint is a URL beginning with http://.

For the https protocol, the (public) endpoint is a URL beginning with https://.

For the email protocol, the endpoint is an email address.

For the email-json protocol, the endpoint is an email address.

For the sms protocol, the endpoint is a phone number of an SMS-enabled device.

For the sqs protocol, the endpoint is the ARN of an Amazon SQS queue.

For the application protocol, the endpoint is the EndpointArn of a mobile app and device.

For the lambda protocol, the endpoint is the ARN of an AWS Lambda function.

For the firehose protocol, the endpoint is the ARN of an Amazon Kinesis Data Firehose delivery stream. '
            )
            ->addOption(
                'attributes',
                'a',
                InputOption::VALUE_OPTIONAL,
                'Json: array of custom strings keys (attributeName) to strings',
                ''
            )
            ->addOption(
                'return-subscription-arn',
                'rs',
                InputOption::VALUE_OPTIONAL,
                'Sets whether the response from the Subscribe request includes the subscription ARN, even if the subscription is not yet confirmed.',
                true
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $topicArn = $input->getArgument('topic');
            $endpoint = $input->getArgument('endpoint');
            $protocol = $input->getArgument('protocol');
            $attributes = json_decode($input->getOption('attributes'));
            $returnSubscription = $input->getOption('return-subscription-arn');

            $topic = $this->topicService->getTopic($topicArn);

            $request = (new SubscriptionRequest())
                ->setTopic($topic)
                ->setEndpoint($endpoint)
                ->setProtocol($protocol)
                ->setReturnSubscriptionArn($returnSubscription)
                ->setAttributes($attributes ?? []);

            $subscription = $this->topicService->subscribe($request);

            $output->write(
                sprintf(
                    'Subscription %s has been requested for the %s topic and is awaiting confirmation',
                    $subscription->getSubscriptionArn(),
                    $topic->getTopicArn()
                ),
            );
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
