<?php

namespace App\Domain\Assessment\Submission;

use App\Domain\Assessment\Task;
use App\Domain\Assessment\TaskType;

final class TaskCompletion
{
    public static function isDone(Task $task, array $submissions): bool
    {
        if($task->type() === TaskType::QUIZ) {
            return $submissions !== [];
        }

        foreach($submissions as $submission) {
            if($submission->status() === SubmissionStatus::ACCEPTED){
                return true;
            }
        }

        return false;
    }

    public static function bestScore(array $submissions): ?int
    {
        $scores = array_filter(
            array_map(fn (TaskSubmission $submission) => $submission->score(), $submissions),
            fn (?int $score) => $score !== null,
        );

        return $scores === [] ? null : max($scores);
    }
}