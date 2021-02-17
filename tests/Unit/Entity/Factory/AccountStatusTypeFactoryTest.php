<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\Entity\Factory;

use App\Entity\AccountStatusType;
use App\Entity\Factory\AccountStatusTypeFactory;
use PHPUnit\Framework\TestCase;

class AccountStatusTypeFactoryTest extends TestCase
{
    /**
     * @covers \App\Entity\Factory\AccountStatusTypeFactory::create()
     */
    public function testCreate()
    {
        $this->assertInstanceOf(AccountStatusType::class, AccountStatusTypeFactory::create());
    }
}
