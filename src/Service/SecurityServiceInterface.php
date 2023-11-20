<?php

namespace App\Service;

use App\Security\User;

interface SecurityServiceInterface
{
    /**
     * Authenticates user by email and password
     *
     * @param string $email
     * @param string $password
     * @return User
     */
    public function authenticate(string $email, string $password): User;
}
