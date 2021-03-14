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
     * @param string $key
     *
     * @return mixed|null
     */
    public function getParameter(string $key)
    {
        return $this->hasParameter($key) ? $this->parameters[$key] : null;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasParameter(string $key): bool
    {
        return \array_key_exists($key, $this->parameters);
    }

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
     * @param        $value
     */
    public function setParameter(string $key, $value)
    {
        $this->parameters[$key] = $value;
    }
}
