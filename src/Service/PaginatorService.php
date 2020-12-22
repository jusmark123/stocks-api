<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service;

use App\Paginator\PaginatorFactory;

/**
 * Class PaginatorService.
 */
class PaginatorService
{
    /**
     * @var PaginatorFactory
     */
    private $factory;

    /**
     * PaginatorService constructor.
     *
     * @param PaginatorFactory $factory
     */
    public function __construct(PaginatorFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param array      $items
     * @param float|int  $currentPage
     * @param float|int  $itemsPerPage
     * @param float|null $totalItems
     * @param float|int  $lastPage
     *
     * @return \App\Paginator\Paginator
     */
    public function createPaginator(
        array $items,
        float $currentPage = 1,
        float $itemsPerPage = 10,
        float $totalItems = 0,
        float $lastPage = 1
    ) {
        return $this->factory->create($items, $currentPage, $itemsPerPage, $totalItems, $lastPage);
    }
}
