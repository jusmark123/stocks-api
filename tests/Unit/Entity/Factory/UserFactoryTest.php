<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\Entity\Factory;

use App\Entity\Factory\UserFactory;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserFactoryTest extends TestCase
{
    /**
     * @covers \App\Entity\Factory\UserFactory::create()
     */
    public function testCreate()
    {
        $this->assertInstanceOf(User::class, UserFactory::create());
    }
}
