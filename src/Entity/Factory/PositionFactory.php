<?php


namespace App\Entity\Factory;


use App\DTO\Brokerage\PositionInterface;
use App\Entity\Position;

/**
 * Class PositionFactory.
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
     * @param PositionInterface $positionInfo
     *
     * @return Position
     */
    public static function create(PositionInterface $positionInfo): Position
    {
        return self::init()->setPositionInfo($positionInfo);
    }
}