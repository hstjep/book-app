<?php

namespace App\Utils\Filter;

interface FilterOptionsInterface
{ 
    public const DEFAULT_PAGE = 1;
    public const DEFAULT_LIMIT = 10;
    public const DEFAULT_ORDER_DIRECTION = 'ASC';

    /**
     * Gets page number
     *
     * @return int
     */
    public function getPage(): int;

    /**
     * Sets page number
     *
     * @param integer $page
     * @return FilterOptionsInterface
     */
    public function setPage(int $page): static;

    /**
     * Gets page size
     *
     * @return int
     */
    public function getPageSize(): int;

    /**
     * Sets page size
     *
     * @param integer $pageSize
     * @return FilterOptionsInterface
     */
    public function setPageSize(int $pageSize): static;

    /**
     * Gets order by
     *
     * @return string|null
     */
    public function getOrderBy(): ?string;

    /**
     * Sets order by
     *
     * @param string|null $orderBy
     * @return FilterOptionsInterface
     */
    public function setOrderBy(?string $orderBy): static;

    /**
     * Gets order direction
     *
     * @return string|null
     */
    public function getOrderDirection(): ?string;

    /**
     * Sets order direction
     *
     * @param string|null $orderDirection
     * @return FilterOptionsInterface
     */
    public function setOrderDirection(?string $orderDirection): static;
}
