<?php

namespace App\Domain\Repository;

use App\Utils\Filter\FilterOptionsInterface;
use App\Utils\Pagination\PagedResult;

interface BookRepositoryInterface
{
    /**
     * Finds books by the given options
     *
     * @param FilterOptionsInterface $options
     * @return PagedResult|null
     */
    public function find(FilterOptionsInterface $options): ?PagedResult;

    /**
     * Gets book by id
     *
     * @param string $id
     * @return array|null
     */
    public function getById(string $id): ?array;

    /**
     * Creates a book
     *
     * @param array $data
     */
    public function create(array $data): ?array;

    /**
     * Removes the book
     *
     * @param string $id
     * @return array|null
     */
    public function remove(string $id): ?array;
}
