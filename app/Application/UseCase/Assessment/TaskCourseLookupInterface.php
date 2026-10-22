<?php

namespace App\Application\UseCase\Assessment;

use App\Domain\Assessment\TaskId;
use App\Domain\Course\CourseId;

interface TaskCourseLookupInterface
{
    public function courseIdOf(TaskId $taskId): ?CourseId;
}
