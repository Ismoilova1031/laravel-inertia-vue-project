<?php

namespace App\Domain\Assessment;

use App\Domain\Assessment\VO\TaskId;
use App\Domain\Course\VO\CourseId;
use App\Domain\Lesson\VO\LessonId;
use Illuminate\Support\Collection;

interface TaskRepositoryInterface
{
    public function findByLessonId(LessonId $lessonId): ?Task;

    public function findById(TaskId $taskId): ?Task;

    public function create(NewTask $new): Task;

    public function save(Task $task): void;

    public function deleteByLessonId(LessonId $lessonId): void;

    public function courseIdOf(TaskId $taskId): ?CourseId;

    public function findByLessonIds(array $lessonIds): Collection;

    public function findByCourse(CourseId $courseId): Collection;
}
