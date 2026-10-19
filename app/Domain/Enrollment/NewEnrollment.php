<?php

namespace App\Domain\Enrollment;

use App\Domain\Course\CourseId;
use App\Domain\Student\StudentId;
use DateTimeImmutable;

final class NewEnrollment
{
    private function __construct(
        public readonly CourseId $courseId,
        public readonly StudentId $studentId,
        public readonly DateTimeImmutable $enrolledAt
    ) {}

    public static function enroll(CourseId $courseId, StudentId $studentId, DateTimeImmutable $now): self
    {
        return new self($courseId, $studentId, $now);
    }

    public function status(): EnrollmentStatus
    {
        return EnrollmentStatus::ACTIVE;
    }
}