<?php

namespace App\Application\Enrollment\DeactivateEnrollment;

final class DeactivateEnrollmentCommand
{
    public function __construct(
        public readonly int $courseId,
        public readonly int $studentId,
    ){}
}