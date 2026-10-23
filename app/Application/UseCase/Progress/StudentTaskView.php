<?php

namespace App\Application\UseCase\Progress;

use App\Domain\Assessment\Submission\TaskSubmission;
use App\Domain\Assessment\Task;

final class StudentTaskView
{
    /** @param list<TaskSubmission> $submissions */
    public function __construct(
        public readonly Task $task,
        public readonly array $submissions,
        public readonly bool $canSubmit,
        public readonly ?string $blockedReason,
        public readonly int $attemptsLeft,
        public readonly ?int $bestScore,
        public readonly int $maxScore,
        public readonly bool $revealAnswers
    ) {}
}
