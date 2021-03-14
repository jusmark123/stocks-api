<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;

class Transaction extends AbstractGuidEntity
{
    private Account $account;

    private string $description = '';

    private Source $source;

    private TransactionType $type;

    private ?Order $order = null;

    private float $amountUsd = 0.00;

    /**
     * @var ArrayCollection|TransactionFee[]|PersistentCollection
     */
    private $fees;

    /**
     * @var ArrayCollection|TransactionLog[]|PersistentCollection
     */
    private $transactionLogs;
}
