<?php

namespace App\Application\UseCase\Assessment;

use App\Domain\Assessment\TaskId;

interface TaskSubmissionLookupInterface
{
    public function exists(TaskId $taskId): bool;
}