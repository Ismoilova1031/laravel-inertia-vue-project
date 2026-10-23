<?php

namespace App\Application\UseCase\Progress;

use App\Domain\Course\Course;
use App\Domain\Enrollment\Enrollment;
use App\Domain\Progress\CourseProgress;

final class StudentCourseView
{
    public function __construct(
        public readonly Course $course,
        public readonly Enrollment $enrollment,
        public readonly array $lessons,
        public readonly array $completedLessonIds,
        public readonly CourseProgress $progress,
    ) {}
}