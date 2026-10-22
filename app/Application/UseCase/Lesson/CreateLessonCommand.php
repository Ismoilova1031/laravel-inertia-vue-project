<?php

namespace App\Application\UseCase\Lesson;

use App\Domain\Shared\UploadedFileData;
use App\Application\UseCase\Assessment\TaskData;

final class CreateLessonCommand
{
    public function __construct(
        public readonly int $courseId,
        public readonly string $title,
        public readonly string $description,
        public readonly int $type,
        public readonly ?string $content = null,
        public readonly ?UploadedFileData $video = null,
        public readonly ?TaskData $task = null,
    ) {}
}
