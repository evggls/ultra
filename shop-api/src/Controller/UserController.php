<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository
    )
    {
    }

    #[Route('/api/user/{email}', name: 'get-user')]
    public function getUserByEmail(string $email = ''): JsonResponse
    {
        if (!$user = $this->userRepository->findOneBy(['email' => $email])) {
            return $this->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'User with email ' . $email . ' does not exit'
            ], 404);
        }

        return $this->json([
            'data' => [
                'user' => [
                    'email' => $user->getEmail(),
                    'roles' => $user->getRoles(),
                ]
            ],
        ]);
    }
}
