<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Behat\Traits;

use Behat\Behat\Hook\Scope\ScenarioScope;
use Behatch\Context\JsonContext;

/**
 * Trait JsonContextTrait.
 */
trait JsonContextTrait
{
    /**
     * @var
     */
    protected $jsonContext;

    /**
     * @BeforeScenario
     *
     * @param ScenarioScope $scope
     */
    public function setJsonContext(ScenarioScope $scope)
    {
        $this->jsonContext = $scope->getEnvironment()->getContext(JsonContext::class);
    }

    /**
     * @return JsonContext|null
     */
    public function getJsonContext(): ?JsonContext
    {
        return $this->jsonContext;
    }
}
