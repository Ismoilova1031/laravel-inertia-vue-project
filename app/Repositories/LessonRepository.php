<?php

namespace App\Repositories;

use App\Contracts\Repositories\LessonRepositoryInterface;
use App\Models\Lesson;
use Illuminate\Support\Collection;

class LessonRepository implements LessonRepositoryInterface
{
    public function create(array $data): Lesson
    {
        return Lesson::create($data);
    }

    public function update(Lesson $lesson, array $data): Lesson
    {
        $lesson->update($data);
        return $lesson->fresh();
    }

    public function updateById(int $id, array $data): Lesson
    {
        $lesson = Lesson::find($id);
        $lesson->update($data);
        return $lesson->fresh();
    }

    public function delete(Lesson $lesson): bool
    {
        return $lesson->delete();
    }

    public function getByCourseId(int $courseId): Collection
    {
        return Lesson::where('course_id', $courseId)->orderBy('sort_order')->get();
    }
}