<?php

namespace App\Infrastructure\Mapper;

use App\Domain\Assessment\Submission\AttemptNumber;
use App\Domain\Assessment\Submission\NewTaskSubmission;
use App\Domain\Assessment\Submission\StudentAnswer;
use App\Domain\Assessment\Submission\SubmissionId;
use App\Domain\Assessment\Submission\TaskSubmission;
use App\Domain\Assessment\TaskId;
use App\Domain\Student\StudentId;
use App\Infrastructure\Eloquent\StudentAnswerModel;
use App\Infrastructure\Eloquent\TaskSubmissionModel;

final class TaskSubmissionMapper
{
    /** $model: answers yuklangan bo'lishi kerak. */
    public function toEntity(TaskSubmissionModel $model): TaskSubmission
    {
        return TaskSubmission::reconstitute(
            new SubmissionId((int) $model->id),
            new TaskId((int) $model->task_id),
            new StudentId((int) $model->student_id),
            new AttemptNumber((int) $model->attempt),
            $model->content,
            $model->file_path,
            $model->status,
            $model->score !== null ? (int) $model->score : null,
            $model->teacher_comment,
            $model->submitted_at,
            $model->answers->map(fn (StudentAnswerModel $a) => new StudentAnswer(
                (int) $a->question_id,
                array_map('intval', $a->selected_option_ids ?? []),
                $a->answer,
                $a->score !== null ? (int) $a->score : null,
                $a->teacher_comment,
            ))->all(),
        );
    }

    /** @return array<string, mixed> */
    public function toAttributes(TaskSubmission $s): array
    {
        return [
            'status' => $s->status(),
            'score' => $s->score(),
            'teacher_comment' => $s->teacherComment(),
        ];
    }

    /** @return array<string, mixed> */
    public function toNewAttributes(NewTaskSubmission $new): array
    {
        return [
            'task_id' => $new->taskId->value(),
            'student_id' => $new->studentId->value(),
            'attempt' => $new->attempt->value(),
            'status' => $new->status,
            'content' => $new->content,
            'file_path' => $new->filePath,
            'score' => $new->score,
            'submitted_at' => $new->submittedAt,
        ];
    }
}