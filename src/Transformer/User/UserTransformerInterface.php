<?php

namespace App\Transformer\User;

use App\Security\User;

interface UserTransformerInterface
{
    /**
     * Transforms user to associative array
     *
     * @param User $user
     * @return array
     */
    public function transform(User $user): array;
}
