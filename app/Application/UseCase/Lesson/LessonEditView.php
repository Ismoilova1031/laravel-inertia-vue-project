<?php

namespace App\Application\UseCase\Lesson;

use App\Domain\Assessment\Task;
use App\Domain\Lesson\Lesson;

final class LessonEditView
{
    public function __construct(public readonly Lesson $lesson, public readonly ?Task $task) {}
}
