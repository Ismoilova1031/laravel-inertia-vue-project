<?php

namespace App\Domain\Lesson\VO;

use InvalidArgumentException;

final class LessonTitle
{
    private readonly string $value;

    public function __construct(string $value)
    {
        $value = trim($value);

        if ($value === '') {
            throw new InvalidArgumentException('Dars nomi bo\'sh bo\'lishi mumkin emas');
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }
}
