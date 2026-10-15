<?php

namespace App\Application\UseCase\Lesson;

interface LessonQueryInterface
{
    public function forEdit(int $lessonId): ?LessonEditView;
}