<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Factory\JobFactory;
use App\Entity\JobItem;
use Phake;
use PHPUnit\Framework\TestCase;

/**
 * Class JobItemTest.
 */
class JobItemTest extends TestCase
{
    /**
     * @var JobItem
     */
    private $entity;

    protected function setUp(): void
    {
        parent::setUp();
        Phake::initAnnotations($this);
        $this->entity = new JobItem();
    }

    /**
     * @covers \App\Entity\JobItem::__construct()
     */
    public function testConstructor()
    {
        $this->assertInstanceOf(JobItem::class, $this->entity);
    }

    /**
     * @covers \App\Entity\JobItem
     */
    public function testGettersAndSetters()
    {
        $data = [];
        $this->assertSame($this->entity, $this->entity->setData($data));
        $this->assertEquals($data, $this->entity->getData());

        $this->assertSame($this->entity, $this->entity->setStatus('string'));
        $this->assertEquals('string', $this->entity->getStatus());

        $this->assertSame($this->entity, $this->entity->setErrorMessage('string'));
        $this->assertEquals('string', $this->entity->getErrorMessage());

        $this->assertSame($this->entity, $this->entity->setErrorTrace('string'));
        $this->assertEquals('string', $this->entity->getErrorTrace());

        $this->assertSame($this->entity, $this->entity->setReceivedAt(new \DateTime()));
        $this->assertNotNull($this->entity->getReceivedAt());
        $this->assertInstanceOf(\DateTime::class, $this->entity->getReceivedAt());

        $this->assertSame($this->entity, $this->entity->setStartedAt(new \DateTime()));
        $this->assertNotNull($this->entity->getStartedAt());
        $this->assertInstanceOf(\DateTime::class, $this->entity->getStartedAt());

        $this->assertSame($this->entity, $this->entity->setProcessedAt(new \DateTime()));
        $this->assertNotNull($this->entity->getProcessedAt());
        $this->assertInstanceOf(\DateTime::class, $this->entity->getProcessedAt());

        $this->assertSame($this->entity, $this->entity->setFailedAt(new \DateTime()));
        $this->assertNotNull($this->entity->getFailedAt());
        $this->assertInstanceOf(\DateTime::class, $this->entity->getFailedAt());

        $this->assertSame($this->entity, $this->entity->setCancelledAt(new \DateTime()));
        $this->assertNotNull($this->entity->getCancelledAt());
        $this->assertInstanceOf(\DateTime::class, $this->entity->getCancelledAt());

        $job = JobFactory::create();
        $this->assertSame($this->entity, $this->entity->setJob($job));
        $this->assertEquals($job, $this->entity->getJob());

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
