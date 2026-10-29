<?php

namespace App\Domain\Review;

final class RatingSummary
{
    public function __construct(
        public readonly float $average, 
        public readonly int $count,
    ) {}
}