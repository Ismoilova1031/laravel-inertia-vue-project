<?php

namespace App\Domain\Course;

use InvalidArgumentException;

final class CourseDescription
{
    private readonly string $value;

    public function __construct(string $value)
    {
        $value = trim($value);

        if ($value === '') {
            throw new InvalidArgumentException('Kurs tavsifi bo\'sh bo\'lishi mumkin emas.');
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }
}
