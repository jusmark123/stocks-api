<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Factory\AccountFactory;
use App\Entity\Factory\OrderFactory;
use App\Entity\Factory\SourceFactory;
use App\Entity\Factory\TickerFactory;
use App\Entity\Position;
use Phake;
use PHPUnit\Framework\TestCase;

class PositionTest extends TestCase
{
    private Position $entity;

    protected function setUp(): void
    {
        parent::setUp();
        Phake::initAnnotations($this);
        $this->entity = new Position();
    }

    public function testConstructor()
    {
        $this->assertInstanceOf(Position::class, $this->entity);
    }

    public function testGettersAndSetters()
    {
        $account = AccountFactory::create();
        $this->assertSame($this->entity, $this->entity->setAccount($account));
        $this->assertEquals($account, $this->entity->getAccount());

        $this->assertSame($this->entity, $this->entity->setQty(1));
        $this->assertEquals(1, $this->entity->getQty());

        $this->assertSame($this->entity, $this->entity->setSymbol('string'));
        $this->assertEquals('string', $this->entity->getSymbol());

        $source = SourceFactory::create();
        $this->assertSame($this->entity, $this->entity->setSource($source));
        $this->assertEquals($source, $this->entity->getSource());

        $positionInfo = new \App\DTO\Brokerage\Alpaca\AlpacaPosition();
        $this->assertSame($this->entity, $this->entity->setPositionInfo($positionInfo));
        $this->assertEquals($positionInfo, $this->entity->getPositionInfo());

        $order = OrderFactory::create();
        $this->assertSame($this->entity, $this->entity->addOrder($order));
        $this->assertContains($order, $this->entity->getOrders());
        $this->assertSame($this->entity, $this->entity->removeOrder($order));
        $this->assertEmpty($this->entity->getOrders());
        $this->assertSame($this->entity, $this->entity->setOrders([$order]));
        $this->assertIsArray($this->entity->getOrders());

        $ticker = TickerFactory::create();
        $this->assertSame($this->entity, $this->entity->setTicker($ticker));
        $this->assertEquals($ticker, $this->entity->getTicker());
    }
}
