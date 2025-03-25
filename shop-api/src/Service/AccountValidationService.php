<?php

namespace App\Service;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class AccountValidationService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }

    public function validateAccount(string $token = ''): array
    {
        $token = trim($token);

        if (strlen($token) === 0) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'Invalid Token'
            ];
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['validationToken' => $token]);

        if (null === $user) {
            return [
                'status' => 'error',
                'code' => 404,
                'message' => 'Invalid Token'
            ];
        }

        if (null !== $user->getValidatedAt()) {
            return [
                'status' => 'error',
                'code' => 422,
                'message' => 'Account is already validated'
            ];
        }

        $user->setValidatedAt(new DateTimeImmutable());

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return [
            'status' => 'success',
            'code' => 200,
            'message' => 'User validated'
        ];
    }
}