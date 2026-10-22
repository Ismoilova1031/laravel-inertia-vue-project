<?php

namespace App\Application\UseCase\Lesson;

use App\Application\UseCase\Assessment\TaskData;
use App\Domain\Shared\UploadedFileData;

final class UpdateLessonCommand
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $description,
        public readonly int $type,
        public readonly int $sortOrder,
        public readonly ?string $content = null,
        public readonly ?UploadedFileData $video = null,
        public readonly ?TaskData $task = null,
    ) {}
}
