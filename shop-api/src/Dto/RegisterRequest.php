<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RegisterRequest
{
    #[Assert\NotBlank]
    #[Assert\Email]
    public ?string $email;

    #[Assert\NotBlank]
    #[Assert\Length(min: 8)]
    public ?string $password;

    #[Assert\NotBlank(message: 'The password confirmation field is required.')]
    public ?string $passwordConfirmation;

    #[Assert\Callback]
    public function validatePasswordConfirmation(ExecutionContextInterface $context): void
    {
        if ($this->password !== $this->passwordConfirmation) {
            $context
                ->buildViolation('Passwords does not match.')
                ->atPath('passwordConfirmation')
                ->addViolation();
        }
    }


    public function __construct(array $input = [])
    {
        $this->email = $input['email'] ?? null;
        $this->password = $input['password'] ?? null;
        $this->passwordConfirmation = $input['passwordConfirmation'] ?? null;
    }
}