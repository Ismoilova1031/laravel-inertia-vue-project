<?php

namespace App\Application\UseCase\Lesson;

final class ReorderLessonsCommand
{
    public function __construct(
        public readonly int $courseId,
        public readonly array $orderedIds,
    ) {}
}