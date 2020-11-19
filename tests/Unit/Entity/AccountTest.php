<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Account;
use App\Entity\Brokerage;
use Phake;
use PHPUnit\Framework\TestCase;

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

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entity = null;
    }

    public function testGettersAndSetters()
    {
        $this->assertSame($this->entity, $this->entity->setAccountNumber('string'));
        $this->assertEquals('string', $this->entity->getAccountNumber());

        $this->assertSame($this->entity, $this->entity->setApiKey('string'));
        $this->assertSame('string', $this->entity->getApiKey());

        $this->assertSame($this->entity, $this->entity->setApiSecret('string'));
        $this->assertEquals('string', $this->entity->getApiSecret());

        $brokerage = new Brokerage();
        $this->assertSame($this->entity, $this->entity->setBrokerage($brokerage));
        $this->assertEquals($brokerage, $this->entity->getBrokerage());

        $this->assertSame($this->entity, $this->entity->setBuyingPower(2.00));
        $this->assertEquals(2.00, $this->entity->getBuyingPower());

        $this->assertSame($this->entity, $this->entity->setCurrency(2.00));
        $this->assertEquals(2.00, $this->entity->getCurrency());

        $this->assertSame($this->entity, $this->entity->setDescription('string'));
        $this->assertEquals('string', $this->entity->getDescription());

        $this->assertSame($this->entity, $this->entity->setEquity(2.00));
        $this->assertEquals(2.00, $this->entity->getEquity());

        $this->assertSame($this->entity, $this->entity->setName('string'));
        $this->assertEquals('string', $this->entity->getName());
    }
}
