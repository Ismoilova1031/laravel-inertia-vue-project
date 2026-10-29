<?php

namespace App\Application\UseCase\Assessment;

use App\Domain\Assessment\Exception\SubmissionNotFoundException;
use App\Domain\Assessment\Exception\TaskNotFoundException;
use App\Domain\Assessment\Submission\VO\SubmissionId;
use App\Domain\Assessment\Submission\TaskSubmissionRepositoryInterface;
use App\Domain\Assessment\TaskRepositoryInterface;
use App\Domain\Notification\NotificationType;
use App\Application\UseCase\Notification\Notifier;

final class GradeAnswerUseCase
{
    public function __construct(
        private readonly TaskSubmissionRepositoryInterface $submissions,
        private readonly TaskRepositoryInterface $tasks,
        private readonly Notifier $notifier,
    ) {}

    public function execute(int $submissionId, int $questionId, int $score, ?string $comment = null): void
    {
        $id = new SubmissionId($submissionId);

        $submission = $this->submissions->findById($id) ?? throw SubmissionNotFoundException::withId($id);
        $task = $this->tasks->findById($submission->taskId())
            ?? throw TaskNotFoundException::withId($submission->taskId());

        $submission->gradeAnswer($task, $questionId, $score, $comment);

        $this->submissions->save($submission);

        $this->notifier->notify(
            $submission->studentId(),
            $this->tasks->courseIdOf($submission->taskId()),
            NotificationType::ANSWER_GRADED,
            'Your answer was graded',
            $comment,
        );
    }
}
