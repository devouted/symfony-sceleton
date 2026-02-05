<?php

namespace App\Dto\Response;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'LoginResponse')]
class LoginResponse
{
    #[OA\Property(example: 'eyJ0eXAiOiJKV1QiLCJhbGc...')]
    public string $token;

    #[OA\Property]
    public UserResponse $user;
}
