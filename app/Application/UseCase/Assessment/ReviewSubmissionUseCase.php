<?php

namespace App\Application\UseCase\Assessment;

use App\Domain\Assessment\Exception\SubmissionNotFoundException;
use App\Domain\Assessment\Submission\VO\SubmissionId;
use App\Domain\Assessment\Submission\TaskSubmissionRepositoryInterface;

final class ReviewSubmissionUseCase
{
    public function __construct(private readonly TaskSubmissionRepositoryInterface $submissions) {}

    public function execute(int $submissionId, bool $accept, ?string $comment = null): void
    {
        $id = new SubmissionId($submissionId);
        $submission = $this->submissions->findById($id) ?? throw SubmissionNotFoundException::withId($id);

        $submission->review($accept, $comment);

        $this->submissions->save($submission);
    }
}
