<?php

// app/Infrastructure/Repository/EloquentTaskSubmissionRepository.php
declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Assessment\Exception\SubmissionNotFoundException;
use App\Domain\Assessment\Submission\AttemptNumber;
use App\Domain\Assessment\Submission\NewTaskSubmission;
use App\Domain\Assessment\Submission\StudentAnswer;
use App\Domain\Assessment\Submission\SubmissionId;
use App\Domain\Assessment\Submission\TaskSubmission;
use App\Domain\Assessment\Submission\TaskSubmissionRepositoryInterface;
use App\Domain\Assessment\TaskId;
use App\Domain\Course\CourseId;
use App\Domain\Student\StudentId;
use App\Infrastructure\Eloquent\StudentAnswerModel;
use App\Infrastructure\Eloquent\TaskSubmissionModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

final class EloquentTaskSubmissionRepository implements TaskSubmissionRepositoryInterface
{
    public function findById(SubmissionId $id): ?TaskSubmission
    {
        $model = TaskSubmissionModel::with('answers')->find($id->value());

        return $model ? $this->toEntity($model) : null;
    }

    public function findByTaskAndStudent(TaskId $taskId, StudentId $studentId): array
    {
        return TaskSubmissionModel::with('answers')
            ->where('task_id', $taskId->value())
            ->where('student_id', $studentId->value())
            ->orderBy('attempt')
            ->get()
            ->map(fn(TaskSubmissionModel $m) => $this->toEntity($m))
            ->all();
    }

    public function create(NewTaskSubmission $new): TaskSubmission
    {
        return DB::transaction(function () use ($new) {
            $model = TaskSubmissionModel::create([
                'task_id' => $new->taskId->value(),
                'student_id' => $new->studentId->value(),
                'attempt' => $new->attempt->value(),
                'status' => $new->status,
                'content' => $new->content,
                'file_path' => $new->filePath,
                'score' => $new->score,
                'submitted_at' => $new->submittedAt,
            ]);
            $now = now();

            $rows = array_map(fn(StudentAnswer $answer) => [
                'task_submission_id' => $model->id,
                'question_id' => $answer->questionId(),
                'selected_option_ids' => json_encode($answer->selectedOptionIds()),
                'answer' => $answer->text(),
                'score' => $answer->score(),
                'teacher_comment' => $answer->teacherComment(),
                'created_at' => $now,
                'updated_at' => $now,
            ], $new->answers);

            if ($rows !== []) {
                StudentAnswerModel::insert($rows);   // bitta so'rov
            }

            return $this->toEntity(TaskSubmissionModel::with('answers')->findOrFail($model->id));
        });
    }

    public function save(TaskSubmission $submission): void
    {
        DB::transaction(function () use ($submission) {
            $model = TaskSubmissionModel::find($submission->id()->value())
                ?? throw SubmissionNotFoundException::withId($submission->id());

            $model->update([
                'status' => $submission->status(),
                'score' => $submission->score(),
                'teacher_comment' => $submission->teacherComment(),
            ]);

            foreach ($submission->answers() as $a) {
                StudentAnswerModel::where('task_submission_id', $model->id)
                    ->where('question_id', $a->questionId())
                    ->update(['score' => $a->score(), 'teacher_comment' => $a->teacherComment()]);
            }
        });
    }

    private function toEntity(TaskSubmissionModel $model): TaskSubmission
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
            $model->answers->map(fn(StudentAnswerModel $a) => new StudentAnswer(
                (int) $a->question_id,
                array_map('intval', $a->selected_option_ids ?? []),
                $a->answer,
                $a->score !== null ? (int) $a->score : null,
                $a->teacher_comment,
            ))->all(),
        );
    }

    public function courseIdOf(TaskId $taskId): ?CourseId
    {
        $id = DB::table('tasks')
            ->join('lessons', 'lessons.id', '=', 'tasks.lesson_id')
            ->where('tasks.id', $taskId->value())
            ->value('lessons.course_id');

        return $id !== null ? new CourseId((int) $id) : null;
    }

    public function existsForTask(TaskId $taskId): bool
    {
        return TaskSubmissionModel::where('task_id', $taskId->value())->exists();
    }

    public function findByStudentAndTaskIds(StudentId $studentId, array $taskIds): Collection
    {
        return TaskSubmissionModel::with('answers')
            ->where('student_id', $studentId->value())
            ->whereIn('task_id', array_map(fn(TaskId $id) => $id->value(), $taskIds))
            ->orderBy('attempt')
            ->get()
            ->map(fn(TaskSubmissionModel $m) => $this->toEntity($m));
    }
}
