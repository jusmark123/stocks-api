<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\Entity\Factory;

use App\Entity\Factory\AccountFactory;
use App\Entity\Factory\OrderStatusTypeFactory;
use App\Entity\Factory\OrderTypeFactory;
use App\Entity\Factory\SourceFactory;
use App\Entity\Order;
use App\Entity\Position;
use Phake;
use PHPUnit\Framework\TestCase;

/**
 * Class OrderTest.
 */
class OrderTest extends TestCase
{
    /**
     * @var Order
     */
    private $entity;

    protected function setUp(): void
    {
        parent::setUp();
        Phake::initAnnotations($this);
        $this->entity = new Order();
    }

    /**
     * @covers \App\Entity\Order::__construct()
     */
    public function testConstructor()
    {
        $this->assertInstanceOf(Order::class, $this->entity);
    }

    /**
     * @covers \App\Entity\Order
     */
    public function testGettersAndSetters()
    {
        $this->assertSame($this->entity, $this->entity->setSymbol('string'));
        $this->assertEquals('string', $this->entity->getSymbol());

        $position = new Position();
        $this->assertSame($this->entity, $this->entity->setPosition($position));
        $this->assertEquals($position, $this->entity->getPosition());

        $source = SourceFactory::create();
        $this->assertSame($this->entity, $this->entity->setSource($source));
        $this->assertEquals($source, $this->entity->getSource());

        $account = AccountFactory::create();
        $this->assertSame($this->entity, $this->entity->setAccount($account));
        $this->assertEquals($account, $this->entity->getAccount());

        $orderInfo = new Order();
        $this->assertSame($this->entity, $this->entity->setOrderInfo($orderInfo));
        $this->assertEquals($orderInfo, $this->entity->getOrderInfo());

        $orderType = OrderTypeFactory::create();
        $this->assertSame($this->entity, $this->entity->setOrderType($orderType));
        $this->assertEquals($orderType, $this->entity->getOrderType());

        $orderStatusType = OrderStatusTypeFactory::create();
        $this->assertSame($this->entity, $this->entity->setOrderStatusType($orderStatusType));
        $this->assertEquals($orderStatusType, $this->entity->getOrderStatusType());

        $this->assertSame($this->entity, $this->entity->setCreatedAt(new \DateTime()));
        $this->assertNotNull($this->entity->getCreatedAt());
        $this->assertInstanceOf(\DateTime::class, $this->entity->getCreatedAt());

        $this->assertSame($this->entity, $this->entity->setModifiedAt(new \DateTime()));
        $this->assertNotNull($this->entity->getModifiedAt());
        $this->assertInstanceOf(\DateTime::class, $this->entity->getModifiedAt());

        $this->assertSame($this->entity, $this->entity->setDeactivatedAt(new \DateTime()));
        $this->assertNotNull($this->entity->getDeactivatedAt());
        $this->assertInstanceOf(\DateTime::class, $this->entity->getDeactivatedAt());

        $this->assertSame($this->entity, $this->entity->setCreatedBy('string'));
        $this->assertEquals('string', $this->entity->getCreatedBy());

        $this->assertSame($this->entity, $this->entity->setModifiedBy('string'));
        $this->assertEquals('string', $this->entity->getModifiedBy());

        $this->assertSame($this->entity, $this->entity->setDeactivatedBy('string'));
        $this->assertEquals('string', $this->entity->getDeactivatedBy());
    }
}
