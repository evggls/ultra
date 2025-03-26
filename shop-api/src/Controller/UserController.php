<?php

namespace App\Controller;

use App\Service\AccountValidationService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    public function __construct(
        private readonly AccountValidationService $accountValidationService,
        private readonly UserService              $userService
    )
    {
    }

    #[Route('/api/user/me', name: 'me', methods: ['GET'])]
    public function getCurrentUserData(): JsonResponse
    {
        return $this->json($this->userService->getCurrentUserData());
    }

    #[Route('/api/user/validate/{token}', name: 'validate-user', methods: ['PATCH'])]
    public function validateUser(string $token = ''): JsonResponse
    {
        $token = trim($token);
        $result = $this->accountValidationService->validateAccount($token);

        return $this->json($result, $result['code'] ?? 500);
    }
}
