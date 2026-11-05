<?php

namespace App\Application\UseCase\CourseInterest;

use App\Domain\Course\Course;
use App\Domain\Course\CourseRepositoryInterface;
use App\Domain\CourseInterest\CourseInterest;
use App\Domain\CourseInterest\CourseInterestRepositoryInterface;
use App\Domain\CourseInterest\InterestType;
use App\Domain\Enrollment\Enrollment;
use App\Domain\Enrollment\EnrollmentRepositoryInterface;
use App\Domain\Review\RatingSummary;
use App\Domain\Review\ReviewRepositoryInterface;
use App\Domain\Student\VO\StudentId;

final class GetCatalogUseCase
{
    public function __construct(
        private readonly CourseRepositoryInterface $courses,
        private readonly CourseInterestRepositoryInterface $interests,
        private readonly EnrollmentRepositoryInterface $enrollments,
        private readonly ReviewRepositoryInterface $reviews
    ) {}

    public function execute(int $studentId): array
    {
        $studentId = new StudentId($studentId);

        $courses = $this->courses->findPublished();
        if ($courses->isEmpty()) {
            return [];
        }

        $interests = $this->interests->findByStudent($studentId)
            ->keyBy(fn (CourseInterest $interest) => $interest->courseId()->value());

        $enrolled = $this->enrollments->findByStudent($studentId)
            ->filter(fn (Enrollment $enrollment) => $enrollment->isActive())
            ->mapWithKeys(fn (Enrollment $enrollment) => [$enrollment->courseId()->value() => true]);

        $ratings = $this->reviews->summaries($courses->map(fn (Course $course) => $course->id())->values()->all());

        return $courses->map(function (Course $course) use ($interests, $enrolled, $ratings) {
            $id = $course->id()->value();
            $interest = $interests->get($id);

            return new CatalogItem(
                $course,
                $interest?->has(InterestType::LIKE) ?? false,
                $interest?->has(InterestType::PLANNED) ?? false,
                $enrolled->has($id),
                $ratings[$id] ?? new RatingSummary(0.0, 0),
            );
        })->values()->all();
    }
}
