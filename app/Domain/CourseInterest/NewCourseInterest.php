<?php

namespace App\Domain\CourseInterest;

use App\Domain\Course\CourseId;
use App\Domain\Student\StudentId;

final class NewCourseInterest
{
    private function __construct(
        public readonly CourseId $courseId,
        public readonly StudentId $studentId,
        private readonly InterestType $type
    ) {}

    public static function with(CourseId $courseId, StudentId $studentId, InterestType $type): self
    {
        return new self($courseId, $studentId, $type);
    }

    public function has(InterestType $type): bool
    {
        return $this->type === $type;
    }
}