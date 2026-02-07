<?php

namespace App\Dto\Request;

use App\Enum\UserRole;
use Symfony\Component\Validator\Constraints as Assert;

class CreateUserRequest
{
    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\Length(min: 6)]
    public string $password;

    #[Assert\NotBlank]
    #[Assert\All([
        new Assert\Choice(callback: [UserRole::class, 'getValues'])
    ])]
    public array $roles = ['ROLE_USER'];
}
