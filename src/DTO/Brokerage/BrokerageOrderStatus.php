<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage;

use App\Entity\Brokerage;

/**
 * Class BrokerageOrderStatus.
 */
class BrokerageOrderStatus implements BrokerageOrderStatusInterface
{
    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string
     */
    private string $description;

    /**
     * @var Brokerage
     */
    private Brokerage $brokerage;

    /**
     * @return string $name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): BrokerageOrderStatus
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string $description
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription(string $description): BrokerageOrderStatus
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Brokerage
     */
    public function getBrokerage(): Brokerage
    {
        return $this->brokerage;
    }

    /**
     * @param Brokerage $brokerage
     *
     * @return BrokerageOrderStatus
     */
    public function setBrokerage(Brokerage $brokerage): BrokerageOrderStatus
    {
        $this->brokerage = $brokerage;

        return $this;
    }
}
