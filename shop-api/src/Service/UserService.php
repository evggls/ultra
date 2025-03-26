<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;

readonly class UserService
{
    public function __construct(
        private Security $security,
    )
    {
    }

    public function getCurrentUserData(): array
    {
        /** @var User $user*/
        $user = $this->security->getUser();

        if (!$user) {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => 'Something were wrong with the request',
            ];
        }

        return [
            'status' => 'success',
            'code' => 200,
            'data' => [
                'user' => [
                    'email' => $user->getEmail(),
                    'roles' => $user->getRoles(),
                    'createdAt' => $user->getCreatedAt(),
                    'updatedAt' => $user->getUpdatedAt(),
                ],
            ]
        ];
    }
}