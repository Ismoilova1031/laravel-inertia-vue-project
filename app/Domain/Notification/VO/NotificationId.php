<?php

namespace App\Domain\Notification\VO;

use InvalidArgumentException;

final class NotificationId
{
    public function __construct(private readonly int $value)
    {
        if ($value <= 0) {
            throw new InvalidArgumentException('NotificationId musbat son bo\'lishi kerak');
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
