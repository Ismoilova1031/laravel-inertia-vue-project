<?php

namespace App\Application\UseCase\Lesson;

use App\Domain\Assessment\TaskRepositoryInterface;
use App\Domain\Lesson\Exception\LessonNotFoundException;
use App\Domain\Lesson\VO\LessonId;
use App\Domain\Lesson\LessonRepositoryInterface;
use App\Domain\Lesson\LessonType;

final class GetLessonUseCase
{
    public function __construct(
        private readonly LessonRepositoryInterface $lessons,
        private readonly TaskRepositoryInterface $tasks,

    ) {}

    public function execute(int $id): LessonEditView
    {
        $lessonId = new LessonId($id);
        $lesson = $this->lessons->findById($lessonId)
            ?? throw LessonNotFoundException::withId($lessonId);

        $task = $lesson->type() === LessonType::TASK ? $this->tasks->findByLessonId($lessonId) : null;

        return new LessonEditView($lesson, $task);
    }
}
