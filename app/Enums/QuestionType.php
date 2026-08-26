<?php

namespace App\Enums;

enum QuestionType: int
{
    case SINGLE_CHOICE = 1;
    case MULTIPLE_SELECT = 2;
    case SHORT_ANSWER = 3;
    case OPEN_ENDED = 4;

    public function label(): string
    {
        return match ($this) {
            self::SINGLE_CHOICE => 'Single Choice',
            self::MULTIPLE_SELECT => 'Multiple Select',
            self::SHORT_ANSWER => 'Short Answer',
            self::OPEN_ENDED => 'Open Ended',
        };
    }
}