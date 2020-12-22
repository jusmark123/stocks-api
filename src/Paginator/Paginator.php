<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Paginator;

use ApiPlatform\Core\DataProvider\PaginatorInterface;

/**
 * Class Paginator.
 */
class Paginator extends \ArrayIterator implements PaginatorInterface, \Iterator
{
    /**
     * @var array
     */
    private $items;

    /**
     * @var float
     */
    private $lastPage;

    /**
     * @var float
     */
    private $totalItems;

    /**
     * @var float
     */
    private $currentPage;

    /**
     * @var float
     */
    private $itemsPerPage;

    /**
     * @param array $items
     * @param float $currentPage
     * @param float $itemsPerPage
     * @param float $totalItems
     * @param float $lastPage
     */
    public function __construct(
        array $items,
        float $currentPage,
        float $itemsPerPage,
        float $totalItems,
        float $lastPage
    ) {
        $this->items = $items;
        $this->currentPage = $currentPage;
        $this->itemsPerPage = $itemsPerPage;
        $this->totalItems = $totalItems;
        $this->lastPage = $lastPage;
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return \count($this->items);
    }

    /**
     * {@inheritDoc}
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * {@inheritDoc}
     */
    public function getLastPage(): float
    {
        return $this->lastPage;
    }

    /**
     * {@inheritDoc}
     */
    public function getTotalItems(): float
    {
        return $this->totalItems;
    }

    /**
     * {@inheritDoc}
     */
    public function getCurrentPage(): float
    {
        return $this->currentPage;
    }

    /**
     * {@inheritDoc}
     */
    public function getItemsPerPage(): float
    {
        return $this->itemsPerPage;
    }
}
