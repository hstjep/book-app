<?php

namespace App\Domain\Repository;

use App\Utils\Filter\FilterOptionsInterface;
use App\Utils\Pagination\PagedResult;

interface AuthorRepositoryInterface
{
    /**
     * Finds authors by the given filter options
     *
     * @param FilterOptionsInterface $options
     * @return PagedResult|null
     */
    public function find(FilterOptionsInterface $options): ?PagedResult;

    /**
     * Gets author by id
     *
     * @param string $id
     * @return array|null
     */
    public function getById(string $id): ?array;

    /**
     * Creates author
     *
     * @param array $data
     * @return array|null
     */
    public function create(array $data): ?array;

    /**
     * Removes author
     *
     * @param string $id
     * @return array|null
     */
    public function remove(string $id): ?array;
}
