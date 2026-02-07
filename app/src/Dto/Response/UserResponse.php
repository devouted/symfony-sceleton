<?php

namespace App\Dto\Response;

use App\Dto\ResponseDtoInterface;

class UserResponse implements ResponseDtoInterface
{
    public int $id;
    public string $email;
    public array $roles;
    public ?string $deletedAt;

    public static function fromEntity(\App\Entity\User $user): self
    {
        $dto = new self();
        $dto->id = $user->getId();
        $dto->email = $user->getEmail();
        $dto->roles = $user->getRoles();
        $dto->deletedAt = $user->getDeletedAt()?->format('Y-m-d H:i:s');
        return $dto;
    }
}
