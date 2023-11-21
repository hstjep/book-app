<?php

namespace App\Infrastructure\Repository;

use App\Common\Constants;
use App\Utils\ApiClient\ApiClientInterface;
use App\Utils\Filter\FilterOptionsInterface;
use App\Utils\Session\SessionFactory;

class BaseRepository
{
    public function __construct(
        private SessionFactory $sessionFactory,
        private ApiClientInterface $apiClient,
        private string $apiBaseUrl
    ) {
    }

    /**
     * Gets data from the given url
     *
     * @param string $url
     * @param FilterOptionsInterface|null $options
     * @param array|null $headers
     * @return array|null
     */
    protected function get(string $url, ?FilterOptionsInterface $options = null, ?array $headers = []): ?array
    {
        $session = $this->sessionFactory->create();
        $token = $session->get(Constants::EXTERNAL_API_TOKEN);
        $params = [];

        if ($options) {
            $params['page'] = $options->getPage();
            $params['limit'] = $options->getPageSize();

            $orderBy = $options->getOrderBy();

            if ($orderBy) {
                $params['orderBy'] = $orderBy;
                $params['direction'] = $options->getOrderDirection();
            }
        }

        $response = $this->apiClient->get(
            $this->apiBaseUrl . $url,
            $params,
            array_merge($headers, [
                'Authorization' => "Bearer $token"
            ])
        );

        return $response;
    }

    /**
     * Posts data to the given url
     *
     * @param string $url
     * @param array $data
     * @param array|null $headers
     * @return array|null
     */
    protected function post(string $url, array $data, ?array $headers = []): ?array
    {
        $session = $this->sessionFactory->create();
        $token = $session->get(Constants::EXTERNAL_API_TOKEN);

        $response = $this->apiClient->post(
            $this->apiBaseUrl . $url,
            $data,
            array_merge($headers, [
                'Authorization' => "Bearer $token"
            ])
        );

        return $response;
    }

    /**
     * Deletes data from the given url
     *
     * @param string $url
     * @param array|null $headers
     * @return array|null
     */
    protected function delete(string $url, ?array $headers = []): ?array
    {
        $session = $this->sessionFactory->create();
        $token = $session->get(Constants::EXTERNAL_API_TOKEN);

        $response = $this->apiClient->delete(
            $this->apiBaseUrl . $url,
            array_merge($headers, [
                'Authorization' => "Bearer $token"
            ])
        );

        return $response;
    }
}
