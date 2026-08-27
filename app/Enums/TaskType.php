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

    public static function fromValue(int $value): self
    {
        return match ($value) {
            1 => self::QUIZ,
            2 => self::FILE,
            3 => self::DISCUSSION,
            default => throw new \InvalidArgumentException("Invalid TaskType value: $value"),
        };
    }
}