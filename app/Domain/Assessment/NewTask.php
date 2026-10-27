<?php

namespace App\Domain\Assessment;

use App\Domain\Assessment\VO\TaskContent;
use App\Domain\Lesson\VO\LessonId;

final class NewTask
{
    private function __construct(
        public readonly LessonId $lessonId,
        public readonly TaskContent $content,
    ) {}

    public static function create(LessonId $lessonId, TaskContent $content): self
    {
        return new self($lessonId, $content);
    }
}
