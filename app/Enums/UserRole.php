<?php

namespace App\Enums;

enum UserRole: int
{
    case ADMIN = 1;
    case TEACHER = 2;

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Admin',
            self::TEACHER => 'Teacher',
        };
    }
}