<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Behat\Traits;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

trait KernelContructorAwareTrait
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * KernelContructorAwareTrait constructor.
     *
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @return KernelInterface
     */
    public function getKernel(): ?KernelInterface
    {
        return $this->kernel;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->kernel->getContainer();
    }
}
