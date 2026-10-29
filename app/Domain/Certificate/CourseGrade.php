<?php

namespace App\Domain\Certificate;

enum CourseGrade: int
{
    case S = 5;
    case A = 4;
    case B = 3;
    case C = 2;
    case D = 1;

    public static function fromPercent(int $percent): ?self
    {
        return match (true) {
            $percent >= 100 => self::S,
            $percent >= 90 => self::A,
            $percent >= 80 => self::B,
            $percent >= 70 => self::C,
            $percent >= 50 => self::D,
            default => null,
        };
    }

    public function label(): string
    {
        return $this->name;
    }
}