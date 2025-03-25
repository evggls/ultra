<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\HttpFoundation\Request;

class AuthenticationFailureHandler implements AuthenticationFailureHandlerInterface
{
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
    {
        $message = $exception->getMessage();

        $data = [
            'code' => 403,
            'message' => $message,
        ];

        if (str_contains($message, 'verify your email before login')) {
            $data['status'] = 'error';
        }

        return new JsonResponse($data, 403);
    }
}
