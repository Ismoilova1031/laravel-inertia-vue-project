<?php

namespace App\Domain\Progress;

use InvalidArgumentException;

final class LessonProgressId
{
    public function __construct(private readonly int $value)
    {
        if($value <= 0){
            throw new InvalidArgumentException('LessonProgressId musbat son bo\'lishi kerak');
        }
    }

    public function value(): int { return $this->value; }
}