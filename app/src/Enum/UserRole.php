<?php

namespace App\Enum;

enum UserRole: string
{
    case ROLE_USER = 'ROLE_USER';
    case ROLE_ADMIN = 'ROLE_ADMIN';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function isValid(string $role): bool
    {
        return in_array($role, self::getValues(), true);
    }
}