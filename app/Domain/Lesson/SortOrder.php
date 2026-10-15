<?php

namespace App\Domain\Lesson;

use InvalidArgumentException;

final class SortOrder
{
    public function __construct(private readonly int $value)
    {
        if($value < 1){
            throw new InvalidArgumentException('Tartib raqami 1 dan kichik bo\'lmasligi kerak');
        }
    }

    public function value(): int { return $this->value; }
    public function equals(self $other): bool { return $this->value === $other->value; }
}
