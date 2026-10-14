<?php

namespace App\Domain\Course;

use InvalidArgumentException;

final class CourseTitle
{
    private readonly string $value;

    public function __construct(string $value)
    {
        $value = trim($value);

        if ($value === '' || mb_strlen($value) > 255) {
            throw new InvalidArgumentException('Kurs nomi bo\'sh bo\'lishi yoki 255 ta belgidan oshmasligi kerak.');
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }
}
