<?php

namespace App\Domain\Student\VO;

use InvalidArgumentException;

final class HashedPassword
{
    public function __construct(private readonly string $value)
    {
        if ($value === '') {
            throw new InvalidArgumentException('Parol hash\'i bo\'sh bo\'lmasligi kerak');
        }
    }

    public function value(): string
    {
        return $this->value;
    }
}
