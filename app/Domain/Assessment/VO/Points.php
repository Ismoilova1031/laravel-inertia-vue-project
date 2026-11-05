<?php

namespace App\Domain\Assessment\VO;

use InvalidArgumentException;

final class Points
{
    public function __construct(private readonly int $value)
    {
        if ($value < 1 || $value > 1000) {
            throw new InvalidArgumentException('Ball 1 dan 1000 gacha bo\'lishi kerak');
        }
    }

    public function value(): int
    {
        return $this->value;
    }
}
