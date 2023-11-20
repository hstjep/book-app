<?php

namespace App\Utils\ApiClient;

use GuzzleHttp\Client;

class ApiClient implements ApiClientInterface
{
    /**
     * Posts data to the given url
     *
     * @param string $url
     * @param array $options
     * @param array|null $headers
     * @return array|null
     */
    public function get(string $url, array $options = [], ?array $headers = []): ?array
    {
        $client = $this->initialize();

        $response = $client->request('GET', $url, [
            'query' => $options,
            'headers' => $this->mergeHeaders($headers)
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Posts data to the given url
     *
     * @param string $url
     * @param array $data
     * @param array|null $headers
     * @return array|null
     */
    public function post(string $url, array $data = [], ?array $headers = []): ?array
    {
        $client = $this->initialize();

        $response = $client->request('POST', $url, [
            'body' => json_encode($data),
            'headers' => $this->mergeHeaders($headers)
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Deletes data from the given url
     *
     * @param string $url
     * @param array|null $headers
     * @return array|null
     */
    public function delete(string $url, ?array $headers = []): ?array
    {
        $client = $this->initialize();

        $response = $client->request('DELETE', $url, [
            'headers' => array_merge([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ], $headers)
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Initializes Guzzle client
     *
     * @return Client
     */
    protected function initialize()
    {
        $client = new Client([
            \GuzzleHttp\RequestOptions::VERIFY => \Composer\CaBundle\CaBundle::getSystemCaRootBundlePath()
        ]);

        return $client;
    }

    /**
     * Merges headers with default headers
     *
     * @param array $headers
     * @return array
     */
    protected function mergeHeaders(array $headers)
    {
        $defaultHeaders = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ];

        return array_merge($defaultHeaders, $headers);
    }
}
