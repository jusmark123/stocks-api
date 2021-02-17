<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\AccountStatusType;
use Phake;
use PHPUnit\Framework\TestCase;

/**
 * Class AccountStatusTypeTest.
 */
class AccountStatusTypeTest extends TestCase
{
    /**
     * @var AccountStatusType
     */
    private $entity;

    protected function setUp(): void
    {
        parent::setUp();
        Phake::initAnnotations($this);
        $this->entity = new AccountStatusType();
    }

    /**
     * @covers \App\Entity\AccountStatusType
     */
    public function testGettersAndSetters()
    {
        $this->assertSame($this->entity, $this->entity->setDescription('string'));
        $this->assertEquals('string', $this->entity->getDescription());

        $this->assertSame($this->entity, $this->entity->setName('string'));
        $this->assertEquals('string', $this->entity->getName());
    }
}
