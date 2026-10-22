<?php

namespace App\Domain\Assessment;

use App\Domain\Course\CourseId;
use App\Domain\Lesson\LessonId;

interface TaskRepositoryInterface
{
    public function findByLessonId(LessonId $lessonId): ?Task;

    public function findById(TaskId $taskId): ?Task;

    public function create(NewTask $new): Task;

    public function save(Task $task): void;

    public function deleteByLessonId(LessonId $lessonId): void;

    public function courseIdOf(TaskId $taskId): ?CourseId;
}
