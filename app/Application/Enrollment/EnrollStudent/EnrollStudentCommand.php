<?php

namespace App\Application\Enrollment\EnrollStudent;

final class EnrollStudentCommand
{
    public function __construct(
        public readonly int $courseId,
        public readonly int $studentId,
    ){}
}