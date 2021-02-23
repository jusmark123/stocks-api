<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Unit\Entity\Manager;

use App\Entity\Manager\EntityManager;

class DummyEntityManagerFixture extends EntityManager
{
    public const ENTITY_CLASS = DummyEntityManagerFixture::class;
}
