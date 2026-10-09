<?php

namespace App\Domain\Student;

use InvalidArgumentException;

final class Username
{
    private readonly string $value;

    public function __construct(string $value)
    {
        $value = mb_strtolower(trim($value));

        if(!preg_match('/^[a-z0-9_]{3,30}$/', $value)){
            throw new InvalidArgumentException(
                'Username 3-30 belgi bo\'lishi va faqat a-z, 0-9, _ dan iborat bo\'lishi kerak.'
            );
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}