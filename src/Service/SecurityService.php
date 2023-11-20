<?php

namespace App\Service;

use App\Utils\ApiClient\ApiClientInterface;
use App\Security\User;

class SecurityService implements SecurityServiceInterface
{
    const API_URL = '/token';

    public function __construct(
        private ApiClientInterface $apiClient,
        private string $apiBaseUrl
    ) {
    }

    /**
     * Authenticates user by email and password
     *
     * @param string $email
     * @param string $password
     * @return User
     */
    public function authenticate(string $email, string $password): User
    {
        $response = $this->apiClient->post($this->apiBaseUrl . self::API_URL, [
            'email' => $email,
            'password' => $password
        ]);

        if ($response === null) {
            return null;
        }

        $userData = array_key_exists('user', $response) ? $response['user'] : null;

        if ($userData === null) {
            return null;
        }

        $user = new User();
        $user->setEmail($userData['email'])
            ->setApiToken($response['token_key'])
            ->setFirstName($userData['first_name'])
            ->setLastName($userData['last_name'])
            ->setGender($userData['gender']);

        return $user;
    }
}
