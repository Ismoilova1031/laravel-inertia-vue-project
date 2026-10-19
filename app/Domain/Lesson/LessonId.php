<?php

namespace App\Domain\Lesson;

use InvalidArgumentException;

final class LessonId
{
    public function __construct(private readonly int $value)
    {
        if($value <= 0){
            throw new InvalidArgumentException('LessonId musbat son bo\'lishi kerak');
        }
    }

    public function value(): string { return $this->value; }
}