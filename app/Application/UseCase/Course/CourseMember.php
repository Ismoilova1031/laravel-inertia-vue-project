<?php

namespace App\Application\UseCase\Course;

use App\Domain\Enrollment\Enrollment;
use App\Domain\Student\Student;

final class CourseMember
{
    public function __construct(
        public readonly Student $student,
        public readonly Enrollment $enrollment,
    ) {}
}
