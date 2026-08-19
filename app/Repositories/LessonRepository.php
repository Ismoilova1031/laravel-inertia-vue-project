<?php

namespace App\Repositories;

use App\Contracts\Repositories\LessonRepositoryInterface;
use App\Models\Lesson;

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
            return false;
        }
        return $lesson;
    }
}