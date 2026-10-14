<?php

namespace App\Application\UseCase\Enrollment;

final class EnrollStudentCommand
{
    public function __construct(
        public readonly int $courseId,
        public readonly int $studentId,
    ){}
}