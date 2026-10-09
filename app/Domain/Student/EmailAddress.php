<?php

namespace App\Domain\Student;

use InvalidArgumentException;

final class EmailAddress
{
    private readonly string $value;

    public function __construct(string $value)
    {
        $value = mb_strtolower(trim($value));

        if(mb_strlen($value) > 255 || !filter_var($value, FILTER_VALIDATE_EMAIL)){
            throw new InvalidArgumentException('Email manzili noto\'g\'ri');
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