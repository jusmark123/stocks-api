<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\DTO\Aws\Sns\Notification;
use App\DTO\Aws\Sns\TopicAttributes;
use App\Entity\Factory\TopicSubscriptionFactory;
use App\Entity\Topic;
use Phake;
use PHPUnit\Framework\TestCase;

class TopicTest extends TestCase
{
    /**
     * @var Topic
     */
    private Topic $entity;

    protected function setUp(): void
    {
        parent::setUp();
        Phake::initAnnotations($this);
        $this->entity = new Topic();
    }

    /**
     * @covers \App\Entity\Topic::__construct()
     */
    public function testConstructor()
    {
        $this->assertInstanceOf(Topic::class, $this->entity);
    }

    /**
     * @covers \App\Entity\Topic
     */
    public function testGettersAndSetters()
    {
        $attributes = new TopicAttributes();
        $this->assertSame($this->entity, $this->entity->setAttributes($attributes));
        $this->assertEquals($attributes, $this->entity->getAttributes());

        $this->assertSame($this->entity, $this->entity->setTags([]));
        $this->assertIsArray($this->entity->getTags());

        $this->assertSame($this->entity, $this->entity->setContentBasedDeduplication(true));
        $this->assertTrue($this->entity->isContentBasedDeduplication());

        $this->assertSame($this->entity, $this->entity->setType('string'));
        $this->assertEquals('string', $this->entity->getType());

        $this->assertSame($this->entity, $this->entity->setName('string'));
        $this->assertEquals('string', $this->entity->getName());

        $this->assertSame($this->entity, $this->entity->setTopicArn('string'));
        $this->assertEquals('string', $this->entity->getTopicArn());

        $this->assertSame($this->entity, $this->entity->setName('string'));
        $this->assertEquals('string', $this->entity->getName());

        $subscription = TopicSubscriptionFactory::init();
        $this->assertSame($this->entity, $this->entity->setSubscriptions([$subscription]));
        $this->assertContains($subscription, $this->entity->getSubscriptions());

        $notification = new Notification();
        $this->assertSame($this->entity, $this->entity->setNotifications([$notification]));
        $this->assertContains($notification, $this->entity->getNotifications());
    }
}
