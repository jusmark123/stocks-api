<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Factory\AccountFactory;
use App\Entity\Factory\JobFactory;
use App\Entity\Factory\OrderFactory;
use App\Entity\User;
use App\Entity\UserType;
use Phake;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * @var User
     */
    private $entity;

    protected function setUp(): void
    {
        parent::setUp();
        Phake::initAnnotations($this);
        $this->entity = new User();
    }

    /**
     * @covers \App\Entity\User::__construct()
     */
    public function testConstructor()
    {
        $this->assertInstanceOf(User::class, $this->entity);
    }

    /**
     * @covers \App\Entity\User
     */
    public function testGettersAndSetters()
    {
        $this->assertSame($this->entity, $this->entity->setUsername('string'));
        $this->assertEquals('string', $this->entity->getUsername());

        $this->assertSame($this->entity, $this->entity->setDescription('string'));
        $this->assertEquals('string', $this->entity->getDescription());

        $this->assertSame($this->entity, $this->entity->setEmail('string'));
        $this->assertEquals('string', $this->entity->getEmail());

        $this->assertSame($this->entity, $this->entity->setPhone('string'));
        $this->assertSame('string', $this->entity->getPhone());

        $this->assertSame($this->entity, $this->entity->setFirstName('string'));
        $this->assertEquals('string', $this->entity->getFirstName());

        $this->assertSame($this->entity, $this->entity->setLastName('string'));
        $this->assertSame('string', $this->entity->getLastName());

        $this->assertSame($this->entity, $this->entity->setPassword('string'));
        $this->assertEquals('string', $this->entity->getPassword());

        $this->assertSame($this->entity, $this->entity->setPlainPassword('string'));
        $this->assertEquals('string', $this->entity->getPlainPassword());

        $this->assertSame($this->entity, $this->entity->setAvatar('string'));
        $this->assertEquals('string', $this->entity->getAvatar());

        $this->assertNull($this->entity->getSalt());

        $userType = new UserType();
        $this->assertSame($this->entity, $this->entity->setUserType($userType));
        $this->assertEquals($userType, $this->entity->getUserType());

        $account = AccountFactory::create();
        $this->assertSame($this->entity, $this->entity->addAccount($account));
        $this->assertContains($account, $this->entity->getAccounts());
        $this->assertSame($this->entity, $this->entity->removeAccount($account));
        $this->assertNotContains($account, $this->entity->getAccounts());
        $this->assertSame($this->entity, $this->entity->setAccounts([]));

        $userType = new UserType();
        $this->assertSame($this->entity, $this->entity->setUserType($userType));
        $this->assertEquals($userType, $this->entity->getUserType());

        $userType = new UserType();
        $this->assertSame($this->entity, $this->entity->setUserType($userType));
        $this->assertEquals($userType, $this->entity->getUserType());

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

        $roles = [];
        $this->assertSame($this->entity, $this->entity->setRoles($roles));
        $this->assertIsArray($this->entity->getRoles());
        $this->assertIsArray($this->entity->toArray());
        $this->assertIsArray($this->entity->__serialize());

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
