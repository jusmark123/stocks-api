<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Account;
use App\Entity\Brokerage;
use App\Entity\Factory\OrderFactory;
use App\Entity\Factory\OrderStatusTypeFactory;
use App\Entity\Factory\OrderTypeFactory;
use App\Entity\Factory\TickerFactory;
use Doctrine\Common\Collections\ArrayCollection;
use Phake;
use PHPUnit\Framework\TestCase;

class BrokerageTest extends TestCase
{
    /**
     * @var Brokerage
     */
    private $entity;

    /**
     * @Mock
     *
     * @var ArrayCollection
     */
    private $accounts;

    protected function setUp(): void
    {
        parent::setUp();
        Phake::initAnnotations($this);
        $this->entity = new Brokerage();
    }

    /**
     * @covers \App\Entity\Brokerage::__construct()
     */
    public function testConstructor()
    {
        $this->assertInstanceOf(Brokerage::class, $this->entity);
    }

    /**
     * @covers \App\Entity\Brokerage
     */
    public function testGettersAndSetters()
    {
        $this->assertSame($this->entity, $this->entity->setApiDocumentUrl('string'));
        $this->assertSame('string', $this->entity->getApiDocumentUrl());

        $this->assertSame($this->entity, $this->entity->setContext('string'));
        $this->assertSame('string', $this->entity->getContext());

        $this->assertSame($this->entity, $this->entity->setDescription('string'));
        $this->assertSame('string', $this->entity->getDescription());

        $this->assertSame($this->entity, $this->entity->setName('string'));
        $this->assertSame('string', $this->entity->getName());

        $this->assertSame($this->entity, $this->entity->setPaperAccounts(true));
        $this->assertTrue($this->entity->hasPaperAccounts());

        $this->assertSame($this->entity, $this->entity->setUrl('string'));
        $this->assertSame('string', $this->entity->getUrl());

        $this->assertSame($this->entity, $this->entity->setName('string'));
        $this->assertSame('string', $this->entity->getName());

        $account = new Account();
        $accounts = [$account];
        $this->assertSame($this->entity, $this->entity->setAccounts($accounts));
        $this->assertContains($account, $this->entity->getAccounts());

        $order = OrderFactory::create();
        $orders = [$order];
        $this->assertSame($this->entity, $this->entity->setOrders($orders));
        $this->assertContains($order, $this->entity->getOrders());

        $ticker = TickerFactory::create();
        $tickers = [$ticker];
        $this->assertSame($this->entity, $this->entity->setTickers($tickers));
        $this->assertContains($ticker, $this->entity->getTickers());

        $orderType = OrderTypeFactory::create();
        $orderTypes = [$orderType];
        $this->assertSame($this->entity, $this->entity->setOrderTypes($orderTypes));
        $this->assertContains($orderType, $this->entity->getOrderTypes());

        $orderStatusType = OrderStatusTypeFactory::create();
        $orderStatusTypes = [$orderStatusType];
        $this->assertSame($this->entity, $this->entity->setOrderStatusTypes($orderStatusTypes));
        $this->assertContains($orderStatusType, $this->entity->getOrderStatusTypes());

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

    /**
     * @covers \App\Entity\Brokerage::getDefaultAccount()
     */
    public function testGetDefaultAccount()
    {
        $collection = new ArrayCollection();
        $account = (new Account())->setDefault(false);
        $defaultAccount = (new Account())->setDefault(true);
        $collection->add($account);
        $collection->add($defaultAccount);
        $this->entity->setAccounts($collection);
        $this->assertEquals($defaultAccount, $this->entity->getDefaultAccount());
    }
}
