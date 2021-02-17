<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Algorithm;
use App\Entity\Factory\SourceFactory;
use Phake;
use PHPUnit\Framework\TestCase;

/**
 * Class AlgorithmTest.
 */
class AlgorithmTest extends TestCase
{
    /**
     * @var Algorithm
     */
    private $entity;

    protected function setUp(): void
    {
        parent::setUp();
        Phake::initAnnotations($this);
        $this->entity = new Algorithm();
    }

    /**
     * @covers \App\Entity\Algorithm::__construct()
     */
    public function testConstructor()
    {
        $this->assertInstanceOf(Algorithm::class, $this->entity);
    }

    /**
     * @covers \App\Entity\Algorithm
     */
    public function testGettersAndSetters()
    {
        $this->assertSame($this->entity, $this->entity->setDescription('string'));
        $this->assertEquals('string', $this->entity->getDescription());

        $this->assertSame($this->entity, $this->entity->setName('string'));
        $this->assertEquals('string', $this->entity->getName());

        $this->assertSame($this->entity, $this->entity->setConfig([]));
        $this->assertIsArray($this->entity->getConfig());

        $source = SourceFactory::create();
        $this->assertSame($this->entity, $this->entity->setSource($source));
        $this->assertEquals($source, $this->entity->getSource());

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
