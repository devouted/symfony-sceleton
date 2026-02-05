<?php

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

class LoginRequestDto
{
    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\Length(min: 6)]
    public string $password;
}
