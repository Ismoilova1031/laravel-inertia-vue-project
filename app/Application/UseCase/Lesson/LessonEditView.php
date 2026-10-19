<?php

namespace App\Application\UseCase\Lesson;

use App\Domain\Lesson\LessonType;

final class LessonEditView
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $description,
        public readonly LessonType $type,
        public readonly ?string $content,
        public readonly ?string $videoPath,
        public readonly int $sortOrder,

        public readonly mixed $task,
    ) {}
}
