<?php

namespace App\Enums;

enum QuestionType: int
{
    case SINGLE_CHOICE = 1;
    case MULTIPLE_SELECT = 2;
    case OPEN_ENDED = 3;

    public function label(): string
    {
        return match ($this) {
            self::SINGLE_CHOICE => 'Single Choice',
            self::MULTIPLE_SELECT => 'Multiple Select',
            self::OPEN_ENDED => 'Open Ended',
        };
    }
}