<?php

namespace App\Infrastructure\Repository;

use App\Domain\Repository\AuthorRepositoryInterface;
use App\Utils\Filter\FilterOptionsInterface;
use App\Utils\Pagination\PagedResult;

class AuthorRepository extends BaseRepository implements AuthorRepositoryInterface
{
    private const API_URL = '/authors';

    /**
     * Finds authors by the given filter options
     *
     * @param FilterOptionsInterface $options
     * @return PagedResult|null
     */
    public function find(FilterOptionsInterface $options): ?PagedResult
    {
        $response = parent::get(self::API_URL, $options);

        if ($response === null) {
            return null;
        }

        return new PagedResult(
            $response['items'],
            $response['current_page'],
            $response['limit'],
            $response['total_pages']
        );
    }

    /**
     * Gets author by id
     *
     * @param string $id
     * @return array|null
     */
    public function getById(string $id): ?array
    {
        return parent::get(self::API_URL . "/$id");
    }

    /**
     * Creates an author
     *
     * @param array $data
     * @return array|null
     */
    public function create(array $data): ?array
    {
        return parent::post(self::API_URL, $data);
    }

    /**
     * Removes the author
     *
     * @param string $id
     * @return array|null
     */
    public function remove(string $id): ?array
    {
        return parent::delete(self::API_URL . "/{$id}");
    }
}
