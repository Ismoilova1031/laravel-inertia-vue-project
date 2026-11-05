<?php

namespace App\Domain\CourseInterest;

use App\Domain\Course\VO\CourseId;
use App\Domain\Student\VO\StudentId;
use Illuminate\Support\Collection;

interface CourseInterestRepositoryInterface
{
    public function findByCourseAndStudent(CourseId $courseId, StudentId $studentId): ?CourseInterest;

    public function create(NewCourseInterest $new): CourseInterest;

    public function save(CourseInterest $courseInterest): void;

    public function delete(CourseInterest $courseInterest): void;

    public function findByStudent(StudentId $studentId): Collection;
}
