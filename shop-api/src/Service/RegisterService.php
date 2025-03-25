<?php

namespace App\Service;

use App\Dto\RegisterRequest;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterService
{
    private Request $request;

    public function __construct(
        private EntityManagerInterface      $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private ValidatorInterface          $validator,
    )
    {
    }

    public function register(Request $request): array
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return [
                'status' => 'error',
                'code' => 400,
                'message' => 'Please fill all the required fields!'
            ];
        }

        $registerDto = new RegisterRequest($data);

        $errors = $this->validator->validate($registerDto);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return [
                'status' => 'error',
                'code' => 400,
                'message' => $errorMessages[array_key_first($errorMessages)]
            ];
        }

        $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $registerDto->email]);
        if ($existingUser) {
            return [
                'status' => 'error',
                'code' => 409,
                'message' => 'This email is already taken'
            ];
        }

        $user = new User();
        $user->setEmail($registerDto->email);
        $user->setPassword($this->passwordHasher->hashPassword($user, $registerDto->password));

        $roleCustomer = $this->entityManager->getRepository(Role::class)->findOneBy(['name' => 'ROLE_CUSTOMER']);
        $user->addRole($roleCustomer);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return [
            'status' => 'success',
            'code' => 201,
            'message' => 'User created'
        ];
    }

}