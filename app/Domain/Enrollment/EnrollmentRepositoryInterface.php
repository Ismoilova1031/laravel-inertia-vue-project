<?php

namespace App\Domain\Enrollment;

use App\Domain\Course\CourseId;
use App\Domain\Student\StudentId;
use Illuminate\Support\Collection;

interface EnrollmentRepositoryInterface
{
    public function findByCourseAndStudent(CourseId $courseId, StudentId $studentId): ?Enrollment;

    public function create(NewEnrollment $enrollment): Enrollment;

    public function save(Enrollment $enrollment): void;

    public function findByCourse(CourseId $courseId): Collection;

    public function findByStudent(StudentId $studentId): Collection;
}
