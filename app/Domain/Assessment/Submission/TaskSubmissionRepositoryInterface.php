<?php

namespace App\Domain\Assessment\Submission;

use App\Domain\Assessment\TaskId;
use App\Domain\Course\CourseId;
use App\Domain\Student\StudentId;
use Illuminate\Support\Collection;

interface TaskSubmissionRepositoryInterface
{
    public function findById(SubmissionId $id): ?TaskSubmission;

    public function findByTaskAndStudent(TaskId $taskId, StudentId $studentId): array;

    public function create(NewTaskSubmission $new): TaskSubmission;

    public function save(TaskSubmission $submission): void;

    public function courseIdOf(TaskId $taskId): ?CourseId;

    public function existsForTask(TaskId $taskId): bool;

    public function findByStudentAndTaskIds(StudentId $studentId, array $taskIds): Collection;
}
