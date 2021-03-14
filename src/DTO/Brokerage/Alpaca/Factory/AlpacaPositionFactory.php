<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Alpaca\Factory;

use App\DTO\Brokerage\Alpaca\AlpacaAccountTradeActivity;
use App\DTO\Brokerage\Alpaca\AlpacaPosition;
use App\Entity\Factory\AbstractFactory;
use App\Entity\Factory\PositionFactory;
use App\Entity\Position;
use App\Helper\SerializerHelper;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * Class AlpacaPositionFactory.
 */
class AlpacaPositionFactory extends AbstractFactory
{
    /**
     * @return AlpacaPosition
     */
    public static function init(): AlpacaPosition
    {
        return new AlpacaPosition();
    }

    /**
     * @param array $data
     *
     * @throws ExceptionInterface
     *
     * @return AlpacaPosition
     */
    public static function create(array $data): AlpacaPosition
    {
        $serializer = SerializerHelper::ObjectNormalizer();

        return $serializer->denormalize($data, AlpacaPosition::class);
    }

    /**
     * @param array $data
     *
     * @throws ExceptionInterface
     *
     * @return Position
     */
    public static function createPosition(array $data): Position
    {
        $position = self::create($data);

        return PositionFactory::init()
            ->setGuid(Uuid::fromString($position->getAssetId()))
            ->setQuantity($position->getQty())
            ->setSide($position->getSide())
            ->setType('equity');
    }

    public static function createPositionFromPositionInfo(AlpacaPosition $alpacaPosition): Position
    {
        return PositionFactory::init()
            ->setSide($alpacaPosition->getSide())
            ->setQutantity($alpacaPosition->getQty())
            ->setSymbol($alpacaPosition->getSymbol());
    }

    public static function createPositionFromActivity(AlpacaAccountTradeActivity $activity): Position
    {
        return PositionFactory::init()
            ->setGuid($activity->getGuid());
    }
}
