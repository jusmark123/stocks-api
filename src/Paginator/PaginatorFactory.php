<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Paginator;

/**
 * Class PaginatorFactory.
 */
class PaginatorFactory
{
    /**
     * @param array $items
     * @param float $currentPage
     * @param float $itemPerPage
     * @param float $totalItems
     * @param float $lastPage
     *
     * @return Paginator
     */
    public function create(array $items, float $currentPage, float $itemPerPage, float $totalItems, float $lastPage)
    {
        return new Paginator($items, $currentPage, $itemPerPage, $totalItems, $lastPage);
    }
}
