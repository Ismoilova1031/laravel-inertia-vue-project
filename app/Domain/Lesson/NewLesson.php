<?php

namespace App\Domain\Lesson;

use App\Domain\Course\VO\CourseId;
use App\Domain\Lesson\VO\LessonTitle;
use App\Domain\Lesson\VO\LessonDescription;
use App\Domain\Lesson\VO\SortOrder;

final class NewLesson
{
    private function __construct(
        public readonly CourseId $courseId,
        public readonly LessonTitle $title,
        public readonly LessonDescription $description,
        public readonly LessonContent $content,
        public readonly SortOrder $sortOrder,
    ) {}

    public static function create(
        CourseId $courseId,
        LessonTitle $title,
        LessonDescription $description,
        LessonContent $content,
        SortOrder $sortOrder,
    ): self {
        return new self($courseId, $title, $description, $content, $sortOrder);
    }
}
