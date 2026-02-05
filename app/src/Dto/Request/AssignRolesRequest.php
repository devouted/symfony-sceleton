<?php

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

class AssignRolesRequest
{
    #[Assert\NotBlank]
    public array $roles;
}
