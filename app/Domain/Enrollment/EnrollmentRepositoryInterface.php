<?php

namespace App\Domain\Enrollment;

use App\Domain\Course\CourseId;
use App\Domain\Student\StudentId;

interface EnrollmentRepositoryInterface
{
    public function findByCourseAndStudent(CourseId $courseId, StudentId $studentId): ?Enrollment;

    public function save(Enrollment $enrollment): Enrollment;
}