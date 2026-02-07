<?php

namespace App\Dto\Request;

use App\Enum\UserRole;
use Symfony\Component\Validator\Constraints as Assert;

class AssignRolesRequest
{
    #[Assert\NotBlank]
    #[Assert\All([
        new Assert\Choice(callback: [UserRole::class, 'getValues'])
    ])]
    public array $roles;
}
