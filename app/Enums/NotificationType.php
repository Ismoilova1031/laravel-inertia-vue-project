<?php

namespace App\Enums;

enum NotificationType: int
{
    case TASK_ASSIGNED = 1;
    case TASK_COMPLETED = 2;
    case LESSON_PUBLISHED = 3;

    public function label(): string
    {
        return match ($this) {
            self::TASK_ASSIGNED => 'Task Assigned',
            self::TASK_COMPLETED => 'Task Completed',
            self::LESSON_PUBLISHED => 'Lesson Published',
        };
    }
}