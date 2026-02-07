<?php

namespace App\Dto\Response;

use App\Dto\ResponseDtoInterface;

class UserResponse implements ResponseDtoInterface
{
    public function __construct(
        public readonly int $id,
        public readonly string $email,
        public readonly array $roles,
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
