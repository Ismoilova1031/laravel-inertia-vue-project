<?php

namespace App\Application\UseCase\Assessment;

final class TaskData
{
    /**
     * @param  list<string>|null  $fileExtensions
     * @param  list<array<string, mixed>>|null  $questions
     */
    public function __construct(
        public readonly int $type,
        public readonly ?array $fileExtensions = null,
        public readonly ?array $questions = null,
    ) {}
}
