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
    protected array $parameters = [];

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     *
     * @return $this
     */
    public function setParameters(array $parameters): self
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getParameter(string $key)
    {
        if (\array_key_exists($key, $this->parameters)) {
            return $this->parameters[$key];
        }
    }
}
