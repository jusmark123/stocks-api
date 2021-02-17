<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Factory\TopicFactory;
use App\Entity\TopicSubscription;
use Phake;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Lazy\LazyUuidFromString;
use Ramsey\Uuid\Uuid;

/**
 * Class TopicSubscriptionTest.
 */
class TopicSubscriptionTest extends TestCase
{
    /**
     * @var TopicSubscription
     */
    private $entity;

    protected function setUp(): void
    {
        parent::setUp();
        Phake::initAnnotations($this);
        $this->entity = new TopicSubscription();
    }

    /**
     * @covers \App\Entity\TopicSubscription::__construct()
     */
    public function testConstructor()
    {
        $this->assertInstanceOf(TopicSubscription::class, $this->entity);
    }

    /**
     * @covers \App\Entity\TopicSubscription
     */
    public function testGettersAndSetters()
    {
        $this->assertSame($this->entity, $this->entity->setEndpoint('string'));
        $this->assertEquals('string', $this->entity->getEndpoint());

        $this->assertSame($this->entity, $this->entity->setProtocol('string'));
        $this->assertEquals('string', $this->entity->getProtocol());

        $this->assertInstanceOf(LazyUuidFromString::class, $this->entity->getGuid());

        $subscriptionArn = 'arn:aws:sns:us-west-2:448507992616:app:56ef12bc-1a58-4f94-a775-5ca34c181a6b';
        $this->assertSame($this->entity, $this->entity->setSubscriptionArn($subscriptionArn));
        $this->assertEquals($subscriptionArn, $this->entity->getSubscriptionArn());

        $this->assertEquals(Uuid::fromString('56ef12bc-1a58-4f94-a775-5ca34c181a6b'), $this->entity->getGuid());

        $this->assertSame($this->entity, $this->entity->setConfirmed(true));
        $this->assertTrue($this->entity->isConfirmed());

        $topic = TopicFactory::create();
        $this->assertSame($this->entity, $this->entity->setTopic($topic));
        $this->assertEquals($topic, $this->entity->getTopic());

        $this->assertSame($this->entity, $this->entity->setAttributes([]));
        $this->assertIsArray($this->entity->getAttributes());
    }
}
