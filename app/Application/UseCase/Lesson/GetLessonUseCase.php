<?php

namespace App\Application\UseCase\Lesson;

use App\Domain\Lesson\Exception\LessonNotFoundException;
use App\Domain\Lesson\LessonId;

final class GetLessonUseCase
{
    public function __construct(private readonly LessonQueryInterface $query) {}

    public function execute(int $id): LessonEditView
    {
        return $this->query->forEdit($id)
            ?? throw LessonNotFoundException::withId(new LessonId($id));
    }
}