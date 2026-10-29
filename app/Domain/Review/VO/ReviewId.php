<?php

namespace App\Domain\Review\VO;

use InvalidArgumentException;

final class ReviewId
{
    public function __construct(private readonly int $value)
    {
        if ($value <= 0) {
            throw new InvalidArgumentException('ReviewId musbat son bo\'lishi kerak');
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
