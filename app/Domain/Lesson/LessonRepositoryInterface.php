<?php

namespace App\Domain\Lesson;

use App\Domain\Course\CourseId;

interface LessonRepositoryInterface
{
    public function findById(LessonId $id): ?Lesson;

    public function findByCourse(CourseId $courseId): array;

    public function save(Lesson $lesson): Lesson;

    public function delete(LessonId $id): void;
}