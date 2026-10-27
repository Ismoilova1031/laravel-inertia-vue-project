<?php

namespace App\Domain\Assessment\Submission;

use App\Domain\Assessment\Exception\SubmissionRuleException;
use App\Domain\Assessment\Submission\VO\AttemptNumber;
use App\Domain\Assessment\Task;
use App\Domain\Assessment\TaskType;

final class SubmissionAttempts
{
    public static function next(Task $task, array $existing): AttemptNumber
    {
        if (count($existing) >= AttemptNumber::MAX) {
            throw SubmissionRuleException::because('Urinishlar tugagan ('.AttemptNumber::MAX.') ta.');
        }

        if ($task->type() !== TaskType::QUIZ) {
            foreach ($existing as $submission) {
                if ($submission->status() === SubmissionStatus::ACCEPTED) {
                    throw SubmissionRuleException::because('Topshiriq allaqachon qabul qilingan.');
                }
                if ($submission->status() === SubmissionStatus::PENDING) {
                    throw SubmissionRuleException::because('Topshiriq ko\'rib chiqilmoqda.');
                }
            }
        }

        return new AttemptNumber(count($existing) + 1);
    }
}
