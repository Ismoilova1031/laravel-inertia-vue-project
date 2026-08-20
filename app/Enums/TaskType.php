<?php

namespace App\Enums;

enum TaskType: int
{
    case QUIZ = 1;
    case FILE = 2;
    case DISCUSSION = 3;

    public function label(): string
    {
        return match ($this) {
            self::QUIZ => 'Quiz',
            self::FILE => 'File',
            self::DISCUSSION => 'Discussion',
        };
    }
}