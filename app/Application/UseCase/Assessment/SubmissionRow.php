<?php

namespace App\Application\UseCase\Assessment;

use App\Domain\Assessment\Submission\TaskSubmission;
use App\Domain\Assessment\Task;
use App\Domain\Lesson\Lesson;
use App\Domain\Student\Student;

final class SubmissionRow
{
    public function __construct(
        public readonly TaskSubmission $submission,
        public readonly Task $task,
        public readonly Lesson $lesson,
        public readonly Student $student
    ){}
}