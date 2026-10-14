<?php

namespace App\Domain\CourseInterest;

use App\Domain\Course\CourseId;
use App\Domain\Student\StudentId;

interface CourseInterestRepositoryInterface
{
    public function findByCourseAndStudent(CourseId $courseId, StudentId $studentId): ?CourseInterest;

    public function save(CourseInterest $courseInterest): CourseInterest;

    public function delete(CourseInterest $courseInterest): void;
}
