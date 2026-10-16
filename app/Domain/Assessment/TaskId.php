<?php

namespace App\Domain\Assessment;

use InvalidArgumentException;

final class TaskId
{
    public function __construct(private readonly int $value)
    {
        if($value <= 0) {
            throw new InvalidArgumentException('Task Id musbat son bo\'lishi kerak');
        }
    }

    public function value(): int { return $this->value; }
}