<?php

namespace App\Domain\Assessment\Submission;

use InvalidArgumentException;

final class SubmissionId
{
    public function __construct(private readonly int $value)
    {
        if ($value <= 0) {
            throw new InvalidArgumentException('SubmissionId musbat son bo\'lishi kerak');
        }
    }

    public function value(): int
    {
        return $this->value;
    }
}
