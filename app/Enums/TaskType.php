<?php

namespace App\Enums;

enum TaskType: int
{
    case TEST = 1;
    case FILE = 2;
    case DISCUSSION = 3;

    public function label(): string
    {
        return match ($this) {
            self::TEST => 'Test',
            self::FILE => 'File',
            self::DISCUSSION => 'Discussion',
        };
    }
}