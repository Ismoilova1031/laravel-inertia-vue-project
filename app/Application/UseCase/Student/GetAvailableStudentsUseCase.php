<?php

namespace App\Application\UseCase\Student;

use App\Domain\Course\CourseId;
use App\Domain\Enrollment\Enrollment;
use App\Domain\Enrollment\EnrollmentRepositoryInterface;
use App\Domain\Student\StudentRepositoryInterface;
use Illuminate\Support\Collection;

final class GetAvailableStudentsUseCase
{
    public function __construct(
        private readonly StudentRepositoryInterface $students,
        private readonly EnrollmentRepositoryInterface $enrollments) {}

    public function execute(int $courseId): Collection
    {
        $activeIds = $this->enrollments->findByCourse(new CourseId($courseId))
            ->filter(fn (Enrollment $enrollment) => $enrollment->isActive())
            ->map(fn (Enrollment $enrollment) => $enrollment->studentId())
            ->values()->all();

        return $this->students->findExcludingIds($activeIds);
    }
}
