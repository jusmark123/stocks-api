<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Entity;

use App\Entity\Factory\TickerFactory;
use App\Entity\Manager\TickerEntityManager;
use App\Entity\Manager\TickerTypeEntityManager;
use App\Entity\Ticker;
use App\Entity\TickerType;
use App\Helper\ValidationHelper;
use App\Service\AbstractService;
use Psr\Log\LoggerInterface;

class TickerEntityService extends AbstractService
{
    /**
     * @var TickerTypeEntityManager
     */
    private $tickerTypeManager;

    public function __construct(
        TickerEntityManager $entityManager,
        TickerTypeEntityManager $tickerTypeManager,
        ValidationHelper $validator,
        LoggerInterface $logger
    ) {
        $this->tickerTypeManager = $tickerTypeManager;
        parent::__construct($entityManager, $validator, $logger);
    }

    /**
     * @param array      $tickerMessage
     * @param TickerType $tickerType
     *
     * @return Ticker|object|null
     */
    public function createTickerFromMessage(array $tickerMessage)
    {
        $new = false;
        if (!$this->entityManager->getEntityManager()->isOpen()) {
            $this->entityManager = $this->entityManager->getEntityManager()->create(
                $this->entityManager->getEntityManager()->getConnection(),
                $this->entityManager->getEntityManager()->getConfiguration()
            );
        }

        $ticker = $this->getEntityManager()
            ->getEntityRepository()
            ->findOneBy(['name' => $tickerMessage['name']]);

        if (!$ticker instanceof Ticker) {
            $new = true;
            $ticker = TickerFactory::create();
        }

        $tickerType = $this->tickerTypeManager
            ->getEntityManager()
            ->getRepository(TickerType::class)
            ->findOneBy(['code' => $tickerMessage['type']]);

        $tickerMessage['type'] = $tickerType;

        foreach ($tickerMessage as $key => $value) {
            $method = 'set'.ucwords($key);
            if (method_exists($ticker, $method)) {
                $ticker->{$method}($value);
            }
        }

        $ticker
            ->setCreatedBy('system_user')
            ->setModifiedBy('system_user');

        $this->validator->validate($ticker);

        if ($new) {
            $this->save($ticker);
        } else {
            $this->entityManager->flush();
        }

        return $ticker;
    }
}
