<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Factory\AccountFactory;
use App\Entity\Factory\JobItemFactory;
use App\Entity\Factory\SourceFactory;
use App\Entity\Factory\UserFactory;
use App\Entity\Job;
use Phake;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * Class JobTest.
 */
class JobTest extends TestCase
{
    /**
     * @var Job
     */
    private $entity;

    protected function setUp(): void
    {
        parent::setUp();
        Phake::initAnnotations($this);
        $this->entity = new Job();
    }

    /**
     * @covers \App\Entity\Job::__construct()
     */
    public function testConstructor()
    {
        $this->assertInstanceOf(Job::class, $this->entity);
    }

    /**
     * @covers \App\Entity\Job
     */
    public function testGettersAndSetters()
    {
        $config = [];
        $this->assertSame($this->entity, $this->entity->setConfig($config));
        $this->assertEquals($config, $this->entity->getConfig());

        $this->assertSame($this->entity, $this->entity->setDescription('string'));
        $this->assertEquals('string', $this->entity->getDescription());

        $this->assertSame($this->entity, $this->entity->setRequestHash('string'));
        $this->assertEquals('string', $this->entity->getRequestHash());

        $this->assertSame($this->entity, $this->entity->setName('string'));
        $this->assertEquals('string', $this->entity->getName());

        $this->assertSame($this->entity, $this->entity->setStatus('string'));
        $this->assertEquals('string', $this->entity->getStatus());

        $this->assertSame($this->entity, $this->entity->setPercentComplete(0.00));
        $this->assertEquals(0.00, $this->entity->getPercentComplete());

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

        $this->assertSame($this->entity, $this->entity->setCompletedAt(new \DateTime()));
        $this->assertNotNull($this->entity->getCompletedAt());
        $this->assertInstanceOf(\DateTime::class, $this->entity->getCompletedAt());

        $source = SourceFactory::create();
        $this->assertSame($this->entity, $this->entity->setSource($source));
        $this->assertEquals($source, $this->entity->getSource());

        $user = UserFactory::create();
        $this->assertSame($this->entity, $this->entity->setUser($user));
        $this->assertEquals($user, $this->entity->getUser());

        $account = AccountFactory::create();
        $this->assertSame($this->entity, $this->entity->setAccount($account));
        $this->assertEquals($account, $this->entity->getAccount());

        $jobItem = JobItemFactory::create();
        $this->assertSame($this->entity, $this->entity->addJobItem($jobItem));
        $this->assertContains($jobItem, $this->entity->getJobItems());
        $this->assertEquals(1, $this->entity->getJobItemCount());

        $this->assertSame($jobItem, $this->entity->getJobItem($jobItem->getGuid()->toString()));
        $this->assertFalse($this->entity->getJobItem(Uuid::uuid4()->toString()));

        $this->assertSame($this->entity, $this->entity->removeJobItem($jobItem));
        $this->assertNotContains($jobItem, $this->entity->getJobItems());
        $this->assertSame($this->entity, $this->entity->setJobItems([$jobItem]));
        $this->assertContains($jobItem, $this->entity->getJobItems());

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
