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

    public function update(int $id, array $data): bool
    {
        $lesson = $this->find($id);
        return $lesson->update($data);
    }

    private function find(int $id): Lesson
    {
        $lesson = Lesson::find($id);
        if (!$lesson) {
            return abort(404, 'Lesson not found');
        }
        return $lesson;
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