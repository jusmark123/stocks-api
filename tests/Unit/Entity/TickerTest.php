<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\DTO\Brokerage\Polygon\PolygonTickerInfo;
use App\Entity\Factory\BrokerageFactory;
use App\Entity\Factory\TickerTypeFactory;
use App\Entity\Ticker;
use Phake;
use PHPUnit\Framework\TestCase;

class TickerTest extends TestCase
{
    /**
     * @var Ticker
     */
    private $entity;

    protected function setUp(): void
    {
        parent::setUp();
        Phake::initAnnotations($this);
        $this->entity = new Ticker();
    }

    /**
     * @covers \App\Entity\Ticker::__construct()
     */
    public function testConstructor()
    {
        $this->assertInstanceOf(Ticker::class, $this->entity);
    }

    /**
     * @covers \App\Entity\Ticker
     */
    public function testGettersAndSetters()
    {
        $this->assertSame($this->entity, $this->entity->setActive(true));
        $this->assertTrue($this->entity->isActive());

        $this->assertSame($this->entity, $this->entity->setCurrency('string'));
        $this->assertEquals('string', $this->entity->getCurrency());

        $this->assertSame($this->entity, $this->entity->setExchange('string'));
        $this->assertEquals('string', $this->entity->getExchange());

        $this->assertSame($this->entity, $this->entity->setMarket('string'));
        $this->assertEquals('string', $this->entity->getMarket());

        $this->assertSame($this->entity, $this->entity->setSector('string'));
        $this->assertEquals('string', $this->entity->getSector());

        $this->assertSame($this->entity, $this->entity->setTicker('string'));
        $this->assertEquals('string', $this->entity->getTicker());

        $tickerInfo = new PolygonTickerInfo();
        $this->assertSame($this->entity, $this->entity->setTickerInfo($tickerInfo));
        $this->assertEquals($tickerInfo, $this->entity->getTickerInfo());

        $this->assertSame($this->entity, $this->entity->setName('string'));
        $this->assertEquals('string', $this->entity->getName());

        $tickerType = TickerTypeFactory::create();
        $this->assertSame($this->entity, $this->entity->setTickerType($tickerType));
        $this->assertEquals($tickerType, $this->entity->getTickerType());

        $this->assertSame($this->entity, $this->entity->setName('string'));
        $this->assertEquals('string', $this->entity->getName());

        $brokerage = BrokerageFactory::create();
        $brokerages = [$brokerage];
        $this->assertSame($this->entity, $this->entity->addBrokerage($brokerage));
        $this->assertContains($brokerage, $this->entity->getBrokerages());
        $this->assertSame($this->entity, $this->entity->setBrokerages($brokerages));

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
    }
}
