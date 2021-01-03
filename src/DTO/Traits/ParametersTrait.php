<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Traits;

/**
 * Trait ParametersTrait.
 */
trait ParametersTrait
{
    /**
     * @var array
     */
    protected $parameters;

    /**
     * @return array
     */
    public function getParameters(): ?array
    {
        return $this->parameters;
    }

    /**
     * @param mixed $parameters
     *
     * @return $this
     */
    public function setParameters($parameters = []): self
    {
        $this->parameters = $parameters;

        return $this;
    }
}
