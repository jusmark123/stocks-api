<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Behat\Traits;

use App\Tests\Behat\DatabaseContext;
use Behat\Behat\Hook\Scope\ScenarioScope;

/**
 * Trait DatabaseContextTrait.
 */
trait DatabaseContextTrait
{
    /**
     * @var
     */
    protected $databaseContext;

    /**
     * @param ScenarioScope $scope
     */
    public function setDatabaseContext(ScenarioScope $scope)
    {
        $this->databaseContext = $scope->getEnvironment()->getContext(DatabaseContext::class);
    }

    /**
     * @return DatabaseContext|null
     */
    public function getDatabaseContext(): ?DatabaseContext
    {
        return $this->databaseContext;
    }
}
