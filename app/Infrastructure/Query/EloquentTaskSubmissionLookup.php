<?php

namespace App\Infrastructure\Query;

use App\Application\UseCase\Assessment\TaskSubmissionLookupInterface;
use App\Domain\Assessment\TaskId;
use Illuminate\Support\Facades\DB;

final class EloquentTaskSubmissionLookup implements TaskSubmissionLookupInterface
{
    public function exists(TaskId $taskId): bool
    {
        return DB::table('task_submissions')->where('task_id', $taskId->value())->exists();
    }
}
