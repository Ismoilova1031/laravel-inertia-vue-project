<?php

namespace App\Application\UseCase\Progress;

use App\Domain\Certificate\Certificate;
use App\Domain\Course\Course;
use App\Domain\Enrollment\Enrollment;
use App\Domain\Progress\CourseProgress;
use App\Domain\Review\RatingSummary;
use App\Domain\Review\Review;

final class StudentCourseView
{
    public function __construct(
        public readonly Course $course,
        public readonly Enrollment $enrollment,
        public readonly array $lessons,
        public readonly array $completedLessonIds,
        public readonly CourseProgress $progress,
        public readonly ?Certificate $certificate,
        public readonly ?Review $review,
        public readonly RatingSummary $rating,
    ) {}
}
