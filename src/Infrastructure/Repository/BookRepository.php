<?php

namespace App\Infrastructure\Repository;

use App\Domain\Repository\BookRepositoryInterface;
use App\Utils\Filter\FilterOptionsInterface;
use App\Utils\Pagination\PagedResult;

class BookRepository extends BaseRepository implements BookRepositoryInterface
{
    private const API_URL = '/books';

    /**
     * Finds books by the given options
     *
     * @param FilterOptionsInterface $options
     * @return PagedResult|null
     */
    public function find(FilterOptionsInterface $options): ?PagedResult
    {
        $response = parent::get(self::API_URL, $options);

        return new PagedResult(
            $response['items'],
            $response['current_page'],
            $response['limit'],
            $response['total_pages']
        );
    }

    /**
     * Gets book by id
     *
     * @param string $id
     * @return array|null
     */
    public function getById(string $id): ?array
    {
        return parent::get(self::API_URL . "/$id");
    }

    /**
     * Creates a book
     *
     * @param array $data
     */
    public function create(array $data): ?array
    {
        $data['author'] = [
            'id' => $data['author']
        ];
        $data['release_date'] = $data['release_date']->format('Y-m-d\TH:i:s.u\Z');

        return parent::post(self::API_URL, $data);
    }

    /**
     * Removes the book
     *
     * @param string $id
     * @return array|null
     */
    public function remove(string $id): ?array
    {
        return parent::delete(self::API_URL . "/{$id}");
    }
}
