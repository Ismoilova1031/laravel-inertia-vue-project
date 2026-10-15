<?php

namespace App\Repositories;

use App\Contracts\Repositories\LessonRepositoryInterface;
use App\Infrastructure\Eloquent\LessonModel;
use Illuminate\Support\Collection;

class LessonRepository implements LessonRepositoryInterface
{
    public function create(array $data): LessonModel
    {
        return LessonModel::create($data);
    }

    public function update(LessonModel $lesson, array $data): LessonModel
    {
        $lesson->update($data);

        return $lesson->fresh();
    }

    public function updateById(int $id, array $data): LessonModel
    {
        $lesson = LessonModel::find($id);
        $lesson->update($data);

        return $lesson->fresh();
    }

    public function delete(LessonModel $lesson): bool
    {
        return $lesson->delete();
    }

    public function getByCourseId(int $courseId): Collection
    {
        return LessonModel::where('course_id', $courseId)->orderBy('sort_order')->get();
    }
}
