<?php

namespace App\Application\UseCase\CourseInterest;

use App\Domain\Course\Course;
use App\Domain\Review\RatingSummary;

final class CatalogItem
{
    public function __construct(
        public readonly Course $course,
        public readonly bool $liked,
        public readonly bool $planned,
        public readonly bool $enrolled,
        public readonly RatingSummary $rating,
    ) {}
}