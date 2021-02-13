<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

abstract class AbstractDataFixture extends Fixture implements OrderedFixtureInterface
{
    protected const REFERENCE_ID = 'referenceId';
    protected const NAME = 'name';
    protected const DESCRIPTION = 'description';
    protected const GUID = 'guid';

    /**
     * @return array
     */
    abstract protected function getData(): array;
}
