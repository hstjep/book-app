<?php

namespace App\Utils\Filter;

class FilterOptions implements FilterOptionsInterface
{
    private $page = FilterOptionsInterface::DEFAULT_PAGE;
    private $pageSize = FilterOptionsInterface::DEFAULT_LIMIT;
    private $orderBy;
    private $orderDirection = FilterOptionsInterface::DEFAULT_ORDER_DIRECTION;

    /**
     * Gets page number
     *
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * Sets page number
     *
     * @param integer $page
     * @return FilterOptionsInterface
     */
    public function setPage(int $page): static
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Gets page size
     *
     * @return int
     */
    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    /**
     * Sets page size
     *
     * @param integer $pageSize
     * @return FilterOptionsInterface
     */
    public function setPageSize(int $pageSize): static
    {
        $this->pageSize = $pageSize;

        return $this;
    }

    /**
     * Gets order by
     *
     * @return string|null
     */
    public function getOrderBy(): ?string
    {
        return $this->orderBy;
    }

    /**
     * Sets order by
     *
     * @param string|null $orderBy
     * @return FilterOptionsInterface
     */
    public function setOrderBy(?string $orderBy): static
    {
        $this->orderBy = $orderBy;

        return $this;
    }

    /**
     * Gets order direction
     *
     * @return string|null
     */
    public function getOrderDirection(): ?string
    {
        return $this->orderDirection;
    }

    /**
     * Sets order direction
     *
     * @param string|null $orderDirection
     * @return FilterOptionsInterface
     */
    public function setOrderDirection(?string $orderDirection): static
    {
        $this->orderDirection = $orderDirection;

        return $this;
    }
}
