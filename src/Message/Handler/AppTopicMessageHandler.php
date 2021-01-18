<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message\Handler;

use App\Message\ApplicationMessage;
use Aws\Sns\SnsClient;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class AppTopicMessageHandler.
 */
class AppTopicMessageHandler implements MessageHandlerInterface
{
    /**
     * @var SnsClient
     */
    private $snsClient;

    /**
     * AppTopicMessageHandler constructor.
     *
     * @param string|null $region
     */
    public function __construct(?string $region = 'us-west-2')
    {
        $this->snsClient = new SnsClient([
            'version' => 'latest',
            'region' => $region,
        ]);
    }

    /**
     * @param ApplicationMessage $message
     */
    public function __invoke(ApplicationMessage $message)
    {
        $this->snsClient->publish([
            'TopicArn' => $message->getTopicArn(),
            'Message' => json_encode($message->getMessage()),
            'MessageStructure' => 'string',
        ]);
    }
}
