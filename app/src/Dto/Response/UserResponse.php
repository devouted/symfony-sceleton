<?php

namespace App\Dto\Response;

use App\Dto\ResponseDtoInterface;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserResponse')]
class UserResponse implements ResponseDtoInterface
{
    public function __construct(
        #[OA\Property(example: 1)]
        public readonly int $id,
        #[OA\Property(example: 'user@example.com')]
        public readonly string $email,
        #[OA\Property(type: 'array', items: new OA\Items(type: 'string'), example: ['ROLE_USER'])]
        public readonly array $roles,
        #[OA\Property(example: null, nullable: true)]
        public readonly ?string $deletedAt
    ) {}

    public static function fromEntity(\App\Entity\User $user): self
    {
        return new self(
            $user->getId(),
            $user->getEmail(),
            $user->getRoles(),
            $user->getDeletedAt()?->format('Y-m-d H:i:s')
        );
    }
}
