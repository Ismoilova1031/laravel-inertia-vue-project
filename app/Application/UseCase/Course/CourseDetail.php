<?php

namespace App\Application\UseCase\Course;

use App\Domain\Course\CourseCategory;
use App\Domain\Course\CourseStatus;

final class CourseDetail
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $description,
        public readonly CourseCategory $category,
        public readonly CourseStatus $status,

        public readonly iterable $lessons,
        public readonly iterable $students,
    ) {}
}
