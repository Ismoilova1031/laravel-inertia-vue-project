<?php

namespace App\Domain\Assessment\Submission;

use App\Domain\Assessment\TaskId;
use App\Domain\Student\StudentId;

interface TaskSubmissionRepositoryInterface
{
    public function findById(SubmissionId $id): ?TaskSubmission;

    public function findByTaskAndStudent(TaskId $taskId, StudentId $studentId): array;

    public function create(NewTaskSubmission $new): TaskSubmission;

    public function save(TaskSubmission $submission): void;
}
