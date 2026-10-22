<?php

namespace App\Domain\Assessment\Submission;

use InvalidArgumentException;

final class AttemptNumber
{
    public const MAX = 3;

    public function __construct(private readonly int $value)
    {
        if ($value < 1 || $value > self::MAX) {
            throw new InvalidArgumentException('Urinish raqami 1dan '.self::MAX.' gacha bo\'lishi kerak');
        }
    }

    public function value(): int
    {
        return $this->value;
    }
}
