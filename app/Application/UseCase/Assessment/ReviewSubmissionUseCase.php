<?php

namespace App\Application\UseCase\Assessment;

use App\Domain\Assessment\Exception\SubmissionNotFoundException;
use App\Domain\Assessment\Submission\VO\SubmissionId;
use App\Domain\Assessment\Submission\TaskSubmissionRepositoryInterface;
use App\Domain\Assessment\TaskRepositoryInterface;
use App\Domain\Notification\NotificationType;
use App\Application\UseCase\Notification\Notifier;

final class ReviewSubmissionUseCase
{
    public function __construct(
        private readonly TaskSubmissionRepositoryInterface $submissions,
        private readonly TaskRepositoryInterface $tasks,
        private readonly Notifier $notifier,
    ) {}

    public function execute(int $submissionId, bool $accept, ?string $comment = null): void
    {
        $id = new SubmissionId($submissionId);

        $submission = $this->submissions->findById($id) ?? throw SubmissionNotFoundException::withId($id);

        $submission->review($accept, $comment);
        $this->submissions->save($submission);

        $this->notifier->notify(
            $submission->studentId(),
            $this->tasks->courseIdOf($submission->taskId()),
            $accept ? NotificationType::FILE_ACCEPTED : NotificationType::FILE_REJECTED,
            $accept ? 'Your file was accepted' : 'Your file was rejected',
            $submission->teacherComment(),
        );
    }
}
