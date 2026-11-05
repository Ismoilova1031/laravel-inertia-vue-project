<?php

namespace App\Application\UseCase\Assessment;

use App\Domain\Shared\UploadedFileData;

final class SubmitTaskCommand
{
    public function __construct(
        public readonly int $taskId,
        public readonly int $studentId,
        public readonly ?array $answers = null,
        public readonly ?string $text = null,
        public readonly ?UploadedFileData $file = null
    ) {}
}
