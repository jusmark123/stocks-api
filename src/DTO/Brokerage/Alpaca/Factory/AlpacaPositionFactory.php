<?php


namespace App\DTO\Brokerage\Alpaca\Factory;


use App\DTO\Brokerage\Alpaca\AlpacaPosition;
use App\DTO\Brokerage\YahooFinance\Entity\YahooFinanceTicker;
use App\Entity\Account;
use App\Entity\Factory\AbstractFactory;
use App\Entity\Factory\PositionFactory;
use App\Entity\Position;
use App\Entity\Ticker;
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
     * @return AlpacaPosition
     * @throws ExceptionInterface
     */
    public static function create(array $data): AlpacaPosition
    {
        $serializer = SerializerHelper::ObjectNormalizer();

        return $serializer->denormalize($data, AlpacaPosition::class);
    }

    /**
     * @param array   $data
     * @param Ticker  $ticker
     * @param Account $account
     *
     * @return Position
     * @throws ExceptionInterface
     */
    public static function createEntity(array $data, Ticker $ticker, Account $account): Position
    {
        $position = self::create($data);

        return PositionFactory::init()
            ->setGuid(Uuid::fromString($position->getAssetId()))
            ->setAccount($account)
            ->setTicker($ticker)
            ->setQuantity($position->getQty())
            ->setSide($position->getSide())
            ->setType('equity');
    }
}