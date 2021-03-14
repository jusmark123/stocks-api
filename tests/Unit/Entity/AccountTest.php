<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\DTO\Brokerage\Alpaca\AlpacaAccountConfiguration;
use App\DTO\Brokerage\Alpaca\Factory\AlpacaAccountSummaryFactory;
use App\Entity\Account;
use App\Entity\Factory\AccountStatusTypeFactory;
use App\Entity\Factory\BrokerageFactory;
use App\Entity\Factory\JobFactory;
use App\Entity\Factory\OrderFactory;
use App\Entity\Factory\UserFactory;
use App\Entity\Position;
use Phake;
use PHPUnit\Framework\TestCase;

/**
 * Class AccountTest.
 */
class AccountTest extends TestCase
{
    /**
     * @var Account
     */
    private $entity;

    protected function setUp(): void
    {
        parent::setUp();
        Phake::initAnnotations($this);
        $this->entity = new Account();
    }

    /**
     * @covers \App\Entity\Account::__construct()
     */
    public function testConstructor()
    {
        $this->assertInstanceOf(Account::class, $this->entity);
    }

    /**
     * @covers \App\Entity\Account
     */
    public function testGettersAndSetters()
    {
        $this->assertSame($this->entity, $this->entity->setApiKey('string'));
        $this->assertSame('string', $this->entity->getApiKey());

        $this->assertSame($this->entity, $this->entity->setApiSecret('string'));
        $this->assertEquals('string', $this->entity->getApiSecret());

        $accountInfo = AlpacaAccountSummaryFactory::create();
        $this->assertSame($this->entity, $this->entity->setAccountInfo($accountInfo));
        $this->assertEquals($accountInfo, $this->entity->getAccountInfo());

        $accountStatusType = AccountStatusTypeFactory::create();
        $this->assertSame($this->entity, $this->entity->setAccountStatusType($accountStatusType));
        $this->assertEquals($accountStatusType, $this->entity->getAccountStatusType());

        $config = new AlpacaAccountConfiguration();
        $this->assertSame($this->entity, $this->entity->setConfiguration($config));
        $this->assertEquals($config, $this->entity->getConfiguration());

        $brokerage = BrokerageFactory::create();
        $this->assertSame($this->entity, $this->entity->setBrokerage($brokerage));
        $this->assertEquals($brokerage, $this->entity->getBrokerage());

        $this->assertSame($this->entity, $this->entity->setDescription('string'));
        $this->assertEquals('string', $this->entity->getDescription());

        $this->assertSame($this->entity, $this->entity->setName('string'));
        $this->assertEquals('string', $this->entity->getName());

        $this->assertSame($this->entity, $this->entity->setPaperAccount(true));
        $this->assertTrue($this->entity->isPaperAccount());

        $this->assertSame($this->entity, $this->entity->setDefault(true));
        $this->assertTrue($this->entity->isDefault());

        $user = UserFactory::create();
        $this->assertSame($this->entity, $this->entity->addUser($user));
        $this->assertContains($user, $this->entity->getUsers());

        $this->assertSame($this->entity, $this->entity->removeUser($user));
        $this->assertNotContains($user, $this->entity->getUsers());

        $users = [$user];
        $this->assertSame($this->entity, $this->entity->setUsers($users));
        $this->assertContains($user, $this->entity->getUsers());

        $position = new Position();
        $this->assertSame($this->entity, $this->entity->addPosition($position));
        $this->assertContains($position, $this->entity->getPositions());

        $this->assertSame($this->entity, $this->entity->removePosition($position));
        $this->assertNotContains($position, $this->entity->getPositions());

        $positions = [$position];
        $this->assertSame($this->entity, $this->entity->setPositions($positions));
        $this->assertContains($position, $this->entity->getPositions());

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
