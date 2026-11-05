<?php

namespace App\Application\UseCase\Enrollment;

final class DeactivateEnrollmentCommand
{
    public function __construct(
        public readonly int $courseId,
        public readonly int $studentId,
    ) {}
}
