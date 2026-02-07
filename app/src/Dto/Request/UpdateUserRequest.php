<?php

namespace App\Dto\Request;

use App\Enum\UserRole;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateUserRequest
{
    #[Assert\Email]
    public ?string $email = null;

    #[Assert\Length(min: 6)]
    public ?string $password = null;

    #[Assert\All([
        new Assert\Choice(callback: [UserRole::class, 'getValues'])
    ])]
    public ?array $roles = null;
}
