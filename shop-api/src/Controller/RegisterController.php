<?php

namespace App\Controller;

use App\Service\UserRegisterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class RegisterController extends AbstractController
{
    public function __construct(
        private readonly UserRegisterService $registerService
    )
    {
    }

    #[Route('/api/register', name: 'register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $result = $this->registerService->register($request);

        return $this->json($result, $result['code'] ?? 500);
    }
}
