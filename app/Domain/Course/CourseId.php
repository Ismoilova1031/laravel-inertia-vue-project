<?php

namespace App\Domain\Course;

use InvalidArgumentException;

final class CourseId
{
    public function __construct(private readonly int $value)
    {
        if ($value <= 0) {
            throw new InvalidArgumentException('CourseId musbat son bo\'lishi kerak');
        }
    }

    public function value(): int
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
