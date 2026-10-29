<?php

namespace App\Domain\Certificate;

use App\Domain\Assessment\Submission\TaskCompletion;
use App\Domain\Assessment\TaskType;

final class CoursePerformance
{
    public static function percent(array $tasks, array $submissionsByTask): ?int
    {
        $earned = 0;
        $max = 0;

        foreach ($tasks as $task) {
            if ($task->type() !== TaskType::QUIZ) {
                continue;
            }

            $max += $task->totalPoints();
            $earned += TaskCompletion::bestScore($submissionsByTask[$task->id()->value()] ?? []) ?? 0;
        }

        return $max === 0 ? null : intdiv($earned * 100, $max);
    }
}