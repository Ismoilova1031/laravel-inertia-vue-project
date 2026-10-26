<?php

namespace App\Application\UseCase\Assessment;

use App\Domain\Assessment\Exception\SubmissionNotFoundException;
use App\Domain\Assessment\Submission\SubmissionId;
use App\Domain\Assessment\Submission\TaskSubmissionRepositoryInterface;
use App\Domain\Shared\FileStorageInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class DownloadSubmissionFileUseCase
{
    public function __construct(
        private readonly TaskSubmissionRepositoryInterface $submissions,
        private readonly FileStorageInterface $storage,
    ) {}

    public function execute(int $submissionId, bool $inline = false): StreamedResponse
    {
        $id = new SubmissionId($submissionId);
        $submission = $this->submissions->findById($id) ?? throw SubmissionNotFoundException::withId($id);

        if ($submission->filePath() === null) {
            throw SubmissionNotFoundException::withId($id);
        }

        return $inline
            ? $this->storage->response($submission->filePath(), true)
            : $this->storage->download($submission->filePath(), true);
    }
}
