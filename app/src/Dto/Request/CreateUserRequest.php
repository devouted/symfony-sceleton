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
    #[Assert\Length(min: 8)]
    #[Assert\Regex(
        pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/',
        message: 'Hasło musi zawierać minimum 8 znaków, w tym: wielką literę, małą literę, cyfrę i znak specjalny (@$!%*?&#)'
    )]
    public string $password;

    #[Assert\NotBlank]
    #[Assert\All([
        new Assert\Choice(callback: [UserRole::class, 'getValues'])
    ])]
    public array $roles = ['ROLE_USER'];
}
