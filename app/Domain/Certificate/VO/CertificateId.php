<?php

namespace App\Domain\Certificate\VO;

use InvalidArgumentException;

final class CertificateId
{
    public function __construct(private readonly int $value)
    {
        if ($value <= 0) {
            throw new InvalidArgumentException('CertificateId musbat son bo\'lishi kerak');
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
