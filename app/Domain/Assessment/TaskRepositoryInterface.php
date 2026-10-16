<?php

namespace App\Domain\Assessment;

use App\Domain\Lesson\LessonId;

interface TaskRepositoryInterface
{
    public function findByLessonId(LessonId $lessonId): ?Task;

    public function save(Task $task): Task;

    public function deleteByLessonId(LessonId $lessonId): void;
}