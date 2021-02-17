<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity;

use App\DTO\Brokerage\OrderInfoInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * class Order.
 *
 * @ORM\Table(
 * 		name="`order`",
 * 		uniqueConstraints={
 * 			@ORM\UniqueConstraint(name="order_un_guid", columns={"guid"}),
 * 		},
 * 		indexes={
 * 			@ORM\Index(name="order_ix_account_id", columns={"account_id"}),
 * 		}
 * )
 * @ORM\Entity(repositoryClass="App\Entity\Repository\OrderRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deactivatedAt", timeAware=false)
 */
class Order extends AbstractGuidEntity
{
    /**
     * @var OrderInfoInterface
     */
    private $orderInfo;

    /**
     * @var string
     *
     * @ORM\Column(name="symbol", type="string", length=10, nullable=false)
     */
    private $symbol;

    /**
     * @var Account
     *
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="orders")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=false)
     */
    private $account;

    /**
     * @var OrderStatusType
     *
     * @ORM\ManyToOne(targetEntity="OrderStatusType")
     * @ORM\JoinColumn(name="order_status_type_id", referencedColumnName="id")
     */
    private $orderStatusType;

    /**
     * @var OrderType
     *
     * @ORM\ManyToOne(targetEntity="OrderType")
     * @ORM\JoinColumn(name="order_type_id", referencedColumnName="id")
     */
    private $orderType;

    /**
     * @var Position|null
     *
     * @ORM\ManyToOne(targetEntity="Position", inversedBy="orders", fetch="LAZY", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="position_id", referencedColumnName="id", nullable=true)
     */
    private $position;

    /**
     * @var Source
     *
     * @ORM\ManyToOne(targetEntity="Source", inversedBy="orders", fetch="LAZY", cascade={"remove"})
     * @ORM\JoinColumn(name="source_id", referencedColumnName="id", nullable=false)
     */
    private $source;

    /**
     * Order constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return OrderInfoInterface
     */
    public function getOrderInfo(): OrderInfoInterface
    {
        return $this->orderInfo;
    }

    /**
     * @param OrderInfoInterface $orderInfo
     *
     * @return Order
     */
    public function setOrderInfo(OrderInfoInterface $orderInfo): Order
    {
        $this->orderInfo = $orderInfo;

        return $this;
    }

    /**
     * @return Position|null
     */
    public function getPosition(): ?Position
    {
        return $this->position;
    }

    /**
     * @param Position|null $position
     *
     * @return $this
     */
    public function setPosition(?Position $position): Order
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * @param string $symbol
     *
     * @return Order
     */
    public function setSymbol(string $symbol): Order
    {
        $this->symbol = $symbol;

        return $this;
    }

    /**
     * @return Account $account
     */
    public function getAccount(): Account
    {
        return $this->account;
    }

    /**
     * @param Account $account
     *
     * @return Order
     */
    public function setAccount(Account $account): Order
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @return Source
     */
    public function getSource(): Source
    {
        return $this->source;
    }

    /**
     * @param Source $source
     *
     * @return Order
     */
    public function setSource(Source $source): Order
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @return OrderType
     */
    public function getOrderType(): OrderType
    {
        return $this->orderType;
    }

    /**
     * @param OrderType $orderType
     *
     * @return Order
     */
    public function setOrderType(OrderType $orderType): Order
    {
        $this->orderType = $orderType;

        return $this;
    }

    /**
     * @return OrderStatusType
     */
    public function getOrderStatusType(): OrderStatusType
    {
        return $this->orderStatusType;
    }

    /**
     * @param OrderStatusType $orderStatusType
     *
     * @return Order
     */
    public function setOrderStatusType(OrderStatusType $orderStatusType): Order
    {
        $this->orderStatusType = $orderStatusType;

        return $this;
    }
}
