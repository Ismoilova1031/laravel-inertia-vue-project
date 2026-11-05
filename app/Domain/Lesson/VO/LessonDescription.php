<?php

namespace App\Domain\Lesson\VO;

use InvalidArgumentException;

final class LessonDescription
{
    private readonly string $value;

    public function __construct(string $value)
    {
        $value = trim($value);

        if (trim($value) === '') {
            throw new InvalidArgumentException('Dars tavsifi bo\'sh bo\'lishi mumkin emas');
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }
}
