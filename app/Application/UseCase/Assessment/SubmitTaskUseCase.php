<?php

namespace App\Application\UseCase\Assessment;

use App\Domain\Assessment\Exception\SubmissionRuleException;
use App\Domain\Assessment\Exception\TaskNotFoundException;
use App\Domain\Assessment\Submission\AttemptNumber;
use App\Domain\Assessment\Submission\NewTaskSubmission;
use App\Domain\Assessment\Submission\SubmissionAttempts;
use App\Domain\Assessment\Submission\SubmissionId;
use App\Domain\Assessment\Submission\TaskSubmissionRepositoryInterface;
use App\Domain\Assessment\Task;
use App\Domain\Assessment\TaskId;
use App\Domain\Assessment\TaskRepositoryInterface;
use App\Domain\Assessment\TaskType;
use App\Domain\Enrollment\EnrollmentRepositoryInterface;
use App\Domain\Enrollment\Exception\StudentNotActiveInCourseException;
use App\Domain\Shared\FileStorageInterface;
use App\Domain\Student\StudentId;
use DateTimeImmutable;
use Throwable;

final class SubmitTaskUseCase
{
    public function __construct(
        private readonly TaskRepositoryInterface $tasks,
        private readonly TaskSubmissionRepositoryInterface $submissions,
        private readonly EnrollmentRepositoryInterface $enrollments,
        private readonly FileStorageInterface $storage,
    ) {}

    public function execute(SubmitTaskCommand $command): SubmissionId
    {
        $taskId = new TaskId($command->taskId);
        $studentId = new StudentId($command->studentId);

        $task = $this->tasks->findById($taskId) ?? throw TaskNotFoundException::withId($taskId);
        $courseId = $this->tasks->courseIdOf($taskId) ?? throw TaskNotFoundException::withId($taskId);

        $enrollment = $this->enrollments->findByCourseAndStudent($courseId, $studentId);
        if ($enrollment === null || ! $enrollment->isActive()) {
            throw StudentNotActiveInCourseException::create();
        }

        $attempt = SubmissionAttempts::next($task, $this->submissions->findByTaskAndStudent($taskId, $studentId));
        $now = new DateTimeImmutable;
        $filePath = null;

        try {
            $new = match ($task->type()) {
                TaskType::QUIZ => NewTaskSubmission::quiz($task, $studentId, $attempt, $command->answers ?? [], $now),
                TaskType::DISCUSSION => NewTaskSubmission::discussion($task, $studentId, $attempt, $command->text ?? '', $now),
                TaskType::FILE => $this->fileSubmission($command, $task, $studentId, $attempt, $now, $filePath),
            };

            return $this->submissions->create($new)->id();
        } catch (Throwable $e) {
            if ($filePath !== null) {
                $this->storage->delete($filePath);
            }
            throw $e;
        }
    }

    private function fileSubmission(
        SubmitTaskCommand $command,
        Task $task,
        StudentId $studentId,
        AttemptNumber $attempt,
        DateTimeImmutable $now,
        ?string &$filePath,
    ): NewTaskSubmission {
        $file = $command->file ?? throw SubmissionRuleException::because('Fayl yuklanmagan.');

        if (! $task->acceptsExtension($file->extension)) {
            throw SubmissionRuleException::because('Bu fayl turi ruxsat etilmagan.');
        }

        $filePath = $this->storage->store("submissions/{$task->id()->value()}/{$studentId->value()}", $file);

        return NewTaskSubmission::file($task, $studentId, $attempt, $filePath, $file->extension, $now);
    }
}
