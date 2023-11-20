<?php

namespace App\Utils\ApiClient;

interface ApiClientInterface
{
    /**
     * Posts data to the given url
     *
     * @param string $url
     * @param array $options
     * @param array|null $headers
     * @return array|null
     */
    public function get(string $url, array $options = [], ?array $headers = []): ?array;

    /**
     * Posts data to the given url
     *
     * @param string $url
     * @param array $data
     * @param array|null $headers
     * @return array|null
     */
    public function post(string $url, array $data = [], ?array $headers = []): ?array;

    /**
     * Deletes data from the given url
     *
     * @param string $url
     * @param array|null $headers
     * @return array|null
     */
    public function delete(string $url, ?array $headers = []): ?array;
}
