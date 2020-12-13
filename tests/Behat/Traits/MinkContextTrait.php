<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Behat\Traits;

use Behat\Behat\Hook\Scope\ScenarioScope;
use Behat\MinkExtension\Context\MinkContext;

/**
 * Trait MinkContextTrait.
 */
trait MinkContextTrait
{
    /**
     * @var
     */
    protected $minkContext;

    /**
     * @BeforeScenario
     *
     * @param ScenarioScope $scope
     */
    public function setMinkContext(ScenarioScope $scope)
    {
        $this->minkContext = $scope->getEnvironment()->getContext(MinkContext::class);
    }

    /**
     * @return MinkContext|null
     */
    public function getMinkContext(): ?MinkContext
    {
        return $this->minkContext;
    }
}
