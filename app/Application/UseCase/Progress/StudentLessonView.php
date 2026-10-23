<?php

namespace App\Application\UseCase\Progress;

use App\Domain\Course\Course;
use App\Domain\Lesson\Lesson;

final class StudentLessonView
{
    public function __construct(
        public readonly Course $course,
        public readonly Lesson $lesson,
        public readonly bool $completed,
        public readonly ?Lesson $previous,
        public readonly ?Lesson $next,
    ) {}
}