<?php

namespace App\Application\UseCase\Assessment;

use App\Domain\Assessment\Exception\TaskLockedException;
use App\Domain\Assessment\TaskRepositoryInterface;
use App\Domain\Assessment\Submission\TaskSubmissionRepositoryInterface;
use App\Domain\Lesson\VO\LessonId;

final class DeleteTaskUseCase
{
    public function __construct(
        private readonly TaskRepositoryInterface $tasks,
        private readonly TaskSubmissionRepositoryInterface $submissions,
    ) {}

    public function execute(int $lessonId): void
    {
        $lesson = new LessonId($lessonId);
        $task = $this->tasks->findByLessonId($lesson);

        if ($task === null) {
            return;
        }

        if ($this->submissions->existsForTask($task->id())) {
            throw TaskLockedException::create();
        }

        $this->tasks->deleteByLessonId($lesson);
    }
}
