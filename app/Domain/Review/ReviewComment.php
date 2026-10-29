<?php

namespace App\Domain\Review;

use InvalidArgumentException;

final class ReviewComment
{
    private readonly ?string $value;

    public function __construct(?string $value)
    {
        $value = $value !== null ? trim($value) : null;
        $value = $value === '' ? null : $value;

        if($value !== null && mb_strlen($value) > 2000) {
            throw new InvalidArgumentException('Izoh 2000 belgidan oshmasligi kerak.');
        }

        $this->value = $value;
    }

    public function value(): ?string { return $this->value; }
}