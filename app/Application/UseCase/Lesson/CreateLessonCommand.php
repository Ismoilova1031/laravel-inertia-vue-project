<?php

namespace App\Application\UseCase\Lesson;

use App\Application\Contracts\VideoFile;
use App\Dtos\TaskDto;

final class CreateLessonCommand
{
    public function __construct(
        public readonly int $courseId,
        public readonly string $title,
        public readonly string $description,
        public readonly int $type,
        public readonly ?string $content = null,
        public readonly ?VideoFile $video = null,
        public readonly ?TaskDto $task = null,
    ) {}
}
