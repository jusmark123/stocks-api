<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Behat\Traits;

use App\Tests\Behat\ExtendedJsonContext;
use Behat\Behat\Hook\Scope\ScenarioScope;

/**
 * Traits ExtendedJsonContextTrait.
 */
trait ExtendedJsonContextTrait
{
    /**
     * @var
     */
    protected $extendedJsonContext;

    /**
     * @param ScenarioScope $scope
     */
    public function setExtendedJsonContext(ScenarioScope $scope)
    {
        $this->extendedJsonContext = $scope->getEnvironment()->getContext(ExtendedJsonContext::class);
    }

    /**
     * @return ExtendedJsonContext|null
     */
    public function getExtendedJsonContext(): ?ExtendedJsonContext
    {
        return $this->extendedJsonContext;
    }
}
