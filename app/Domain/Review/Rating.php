<?php

namespace App\Domain\Review;

use InvalidArgumentException;

final class Rating
{
    public function __construct(private readonly int $value)
    {
        if($value < 1 || $value > 5) {
            throw new InvalidArgumentException('Baho 1 dan 5 gacha bo\'lishi kerak.');
        }
    }

    public function value(): int { return $this->value; }
}