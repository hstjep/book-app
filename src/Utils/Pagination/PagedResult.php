<?php

namespace App\Utils\Pagination;

class PagedResult
{
    public $items = [];
    public $pagination = null;

    /**
     * Initializes PagedResult
     *
     * @param array $items
     * @param integer $page
     * @param integer $limit
     * @param integer $totalPages
     */
    public function __construct(array $items, int $page = 1, int $limit = 12, int $totalPages = 0)
    {
        $this->items = $items;

        $this->pagination = [
            'page' => $page,
            'limit' => $limit,
            'total_pages' => $totalPages
        ];
    }

    /**
     * Gets items
     *
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Sets items
     *
     * @param array $items
     * @return void
     */
    public function setItems(array $items)
    {
        $this->items = $items;
    }

    /**
     * Gets pagination
     *
     * @return array
     */
    public function getPagination()
    {
        return $this->pagination;
    }
}
