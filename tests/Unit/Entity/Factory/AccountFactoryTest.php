<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\Entity\Factory;

use App\Entity\Account;
use App\Entity\Factory\AccountFactory;
use PHPUnit\Framework\TestCase;

class AccountFactoryTest extends TestCase
{
    /**
     * @covers \App\Entity\Factory\AccountFactory::create()
     */
    public function testCreate()
    {
        $this->assertInstanceOf(Account::class, AccountFactory::create());
    }
}
