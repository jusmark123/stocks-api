<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\TickerType;
use Phake;
use PHPUnit\Framework\TestCase;

/**
 * Class TickerTypeTest.
 */
class TickerTypeTest extends TestCase
{
    /**
     * @var TickerType
     */
    private $entity;

    protected function setUp(): void
    {
        parent::setUp();
        Phake::initAnnotations($this);
        $this->entity = new TickerType();
    }

    /**
     * @covers \App\Entity\TickerType
     */
    public function testGettersAndSetters()
    {
        $this->assertSame($this->entity, $this->entity->setCode('string'));
        $this->assertEquals('string', $this->entity->getCode());

        $this->assertSame($this->entity, $this->entity->setName('string'));
        $this->assertEquals('string', $this->entity->getName());

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
