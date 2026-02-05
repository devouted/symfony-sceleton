<?php

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateUserRequest
{
    #[Assert\Email]
    public ?string $email = null;

    #[Assert\Length(min: 6)]
    public ?string $password = null;

    public ?array $roles = null;
}
