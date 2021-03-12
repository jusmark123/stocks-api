<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\DTO\Brokerage\Alpaca\AlpacaPosition;
use App\Entity\Factory\JobFactory;
use App\Entity\Factory\OrderFactory;
use App\Entity\Source;
use App\Entity\SourceType;
use Doctrine\Common\Collections\ArrayCollection;
use Phake;
use PHPUnit\Framework\TestCase;

class SourceTest extends TestCase
{
    /**
     * @var Source
     */
    private $entity;

    protected function setUp(): void
    {
        parent::setUp();
        Phake::initAnnotations($this);
        $this->entity = new Source();
    }

    /**
     * @covers \App\Entity\Source::__construct()
     */
    public function testConstructor()
    {
        $this->assertInstanceOf(Source::class, $this->entity);
    }

    /**
     * @covers \App\Entity\Source
     */
    public function testGettersAndSetters()
    {
        $this->assertSame($this->entity, $this->entity->setId(1));
        $this->assertEquals(1, $this->entity->getId());

        $this->assertSame($this->entity, $this->entity->setDescription('string'));
        $this->assertEquals('string', $this->entity->getDescription());

        $this->assertSame($this->entity, $this->entity->setName('string'));
        $this->assertEquals('string', $this->entity->getName());

        $sourceType = new SourceType();
        $this->assertSame($this->entity, $this->entity->setSourceType($sourceType));
        $this->assertEquals($sourceType, $this->entity->getSourceType());

        $order = OrderFactory::create();
        $this->assertSame($this->entity, $this->entity->addOrder($order));
        $this->assertContains($order, $this->entity->getOrders());

        $this->assertSame($this->entity, $this->entity->removeOrder($order));
        $this->assertNotContains($order, $this->entity->getOrders());

        $orders = [$order];
        $this->assertSame($this->entity, $this->entity->setOrders($orders));
        $this->assertContains($order, $this->entity->getOrders());

        $job = JobFactory::create();
        $jobs = [$job];
        $this->assertSame($this->entity, $this->entity->setJobs($jobs));
        $this->assertContains($job, $this->entity->getJobs());

        $position = new AlpacaPosition();
        $positions = new ArrayCollection([$position]);
        $this->assertSame($this->entity, $this->entity->setPositions($positions));
        $this->assertContains($position, $this->entity->getPositions());

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
