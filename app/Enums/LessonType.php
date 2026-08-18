<?php

namespace App\Enums;

enum LessonType: int
{
    case VIDEO = 1;
    case TEXT = 2;
    case TASK = 3;

    public function label(): string
    {
        return match ($this) {
            self::VIDEO => 'Video',
            self::TEXT => 'Text',
            self::TASK => 'Task',
        };
    }
}