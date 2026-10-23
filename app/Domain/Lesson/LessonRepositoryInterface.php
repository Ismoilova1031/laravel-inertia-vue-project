<?php

namespace App\Domain\Lesson;

use App\Domain\Course\CourseId;
use Illuminate\Support\Collection;

interface LessonRepositoryInterface
{
    public function findById(LessonId $id): ?Lesson;

    public function findByCourse(CourseId $courseId): array;

    public function create(NewLesson $new): Lesson;

    public function save(Lesson $lesson): void;

    public function delete(LessonId $id): void;

    public function countByCourse(CourseId $courseId): int;

    public function findByCourseIds(array $courseIds): Collection;
}
