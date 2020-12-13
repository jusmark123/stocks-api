<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Behat\Traits;

use Behat\Behat\Hook\Scope\ScenarioScope;
use Behatch\Context\RestContext;

/**
 * Trait RestContextTrait.
 */
trait RestContextTrait
{
    /**
     * @var
     */
    protected $restContext;

    /**
     * @BeforeScenario
     *
     * @param ScenarioScope $scope
     */
    public function setRestContext(ScenarioScope $scope)
    {
        $this->restContext = $scope->getEnvironment()->getContext(RestContext::class);
    }

    /**
     * @return RestContext|null
     */
    public function getRestContext(): ?RestContext
    {
        return $this->restContext;
    }
}
