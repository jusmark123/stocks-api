<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Message;

use App\Entity\AbstractEntity;
use App\Entity\Factory\TickerFactory;
use App\Entity\Job;
use App\Entity\Ticker;
use App\Entity\TickerType;
use App\Helper\ValidationHelper;
use App\MessageClient\ClientPublisher\ClientPublisher;
use App\MessageClient\Protocol\MessageFactory;
use App\MessageClient\Protocol\Packet;
use App\Service\DefaultTypeService;
use App\Service\TickerService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use React\Promise\ExtendedPromiseInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class TickerMessageService.
 */
class TickerMessageService extends AbstractMessageService
{
    /**
     * @var TickerService
     */
    private $tickerService;

    /**
     * TickerMessageService constructor.
     *
     * @param DefaultTypeService       $defaultTypeService
     * @param EntityManagerInterface   $entityManager
     * @param EventDispatcherInterface $dispatcher
     * @param LoggerInterface          $logger
     * @param MessageFactory           $messageFactory
     * @param ClientPublisher          $publisher
     * @param TickerService            $tickerService
     * @param ValidationHelper         $validator
     */
    public function __construct(
        DefaultTypeService $defaultTypeService,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $dispatcher,
        LoggerInterface $logger,
        MessageFactory $messageFactory,
        ClientPublisher $publisher,
        TickerService $tickerService,
        ValidationHelper $validator
    ) {
        $this->tickerService = $tickerService;
        parent::__construct(
            $defaultTypeService,
            $entityManager,
            $dispatcher,
            $logger,
            $messageFactory,
            $publisher,
            $validator
        );
    }

    /**
     * @param array    $message
     * @param Job|null $job
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @return AbstractEntity
     */
    public function createFromMessage(array $message, ?Job $job = null)
    {
        try {
            $this->checkConnection();
            $ticker = $this->entityManager
                ->getRepository(Ticker::class)
                ->findOneBy(['name' => $message['name']]);

            if (!$ticker instanceof Ticker) {
                $ticker = TickerFactory::create();
            }

            $message['type'] = $this->entityManager
                ->getRepository(TickerType::class)
                ->find($message['type']);

            $ticker = $this->buildEntityFromMessage($ticker, $message);

            $this->save($ticker);
        } catch (\Exception $e) {
            throw $e;
        }

        return $ticker;
    }

    public function receive(Packet $packet): ExtendedPromiseInterface
    {
        // TODO: Implement receive() method.
    }
}
