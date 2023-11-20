<?php

namespace App\Transformer\User;

use App\Security\User;

class UserTransformer implements UserTransformerInterface
{
    /**
     * Transforms user to associative array
     *
     * @param User $user
     * @return array
     */
    public function transform(User $user): array
    {
        return [
            'email' => $user->getEmail(),
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName(),
            'gender' => $user->getGender()
        ];
    }
}
