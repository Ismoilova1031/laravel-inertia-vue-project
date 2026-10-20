<?php

namespace App\Application\UseCase\Assessment;

use App\Application\Contracts\VideoFile;

final class SubmitTaskCommand
{
    public function __construct(
        public readonly int $taskId,
        public readonly int $studentId,
        public readonly ?array $answers = null,
        public readonly ?string $text = null,
        public readonly ?VideoFile $file = null
    ) {}
}