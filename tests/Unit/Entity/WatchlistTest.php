<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Factory\AccountFactory;
use App\Entity\Factory\TickerFactory;
use App\Entity\Watchlist;
use Phake;
use PHPUnit\Framework\TestCase;

class WatchlistTest extends TestCase
{
    /**
     * @var Watchlist
     */
    private $entity;

    protected function setUp(): void
    {
        parent::setUp();
        Phake::initAnnotations($this);
        $this->entity = new Watchlist();
    }

    /**
     * @covers \App\Entity\Watchlist::__construct()
     */
    public function testConstructor()
    {
        $this->assertInstanceOf(Watchlist::class, $this->entity);
    }

    /**
     * @covers \App\Entity\Watchlist
     */
    public function testGettersAndSetters()
    {
        $this->assertSame($this->entity, $this->entity->setDescription('string'));
        $this->assertEquals('string', $this->entity->getDescription());

        $this->assertSame($this->entity, $this->entity->setName('string'));
        $this->assertEquals('string', $this->entity->getName());

        $this->assertSame($this->entity, $this->entity->setType('string'));
        $this->assertEquals('string', $this->entity->getType());

        $account = AccountFactory::create();
        $this->assertSame($this->entity, $this->entity->setAccount($account));
        $this->assertEquals($account, $this->entity->getAccount());

        $ticker = TickerFactory::create();
        $tickers = [$ticker];
        $this->assertSame($this->entity, $this->entity->setTickers($tickers));
        $this->assertContains($ticker, $this->entity->getTickers());

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
