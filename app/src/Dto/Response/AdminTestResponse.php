<?php

namespace App\Dto\Response;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'AdminTestResponse')]
class AdminTestResponse
{
    #[OA\Property(example: 'Admin access granted')]
    public string $message;

    #[OA\Property(example: 'test@example.com')]
    public string $user;
}
