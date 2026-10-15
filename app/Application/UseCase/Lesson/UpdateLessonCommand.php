<?php

namespace App\Application\UseCase\Lesson;

use App\Application\Contracts\VideoFile;
use App\Dtos\TaskDto;

final class UpdateLessonCommand
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $description,
        public readonly int $type,
        public readonly int $sortOrder,
        public readonly ?string $content = null,
        public readonly ?VideoFile $video = null,
        public readonly ?TaskDto $task = null,
    ) {}
}
