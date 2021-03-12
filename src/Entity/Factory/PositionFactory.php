<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Factory;

use App\DTO\Brokerage\BrokeragePositionInterface;
use App\Entity\Position;

/**
 * Class AlpacaPositionFactory.
 */
class PositionFactory extends AbstractFactory
{
    /**
     * @return Position
     */
    public static function init(): Position
    {
        return new Position();
    }

    /**
     * @param BrokeragePositionInterface $positionInfo
     *
     * @return Position
     */
    public static function create(BrokeragePositionInterface $positionInfo): Position
    {
        return self::init()
            ->setPositionInfo($positionInfo);
    }
}
