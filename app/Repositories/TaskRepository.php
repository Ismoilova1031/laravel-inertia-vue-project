<?php

namespace App\Repositories;

use App\Contracts\Repositories\TaskRepositoryInterface;
use App\Models\Task;
use Illuminate\Support\Collection;

class TaskRepository implements TaskRepositoryInterface
{
    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(Task $task, array $data): Task
    {
        $task->update($data);
        return $task->fresh();
    }

    public function deleteByLessonId(int $lessonId): bool
    {
        return Task::where('lesson_id', $lessonId)->delete();
    }

    public function getByLessonId(int $lessonId): Collection
    {
        return Task::where('lesson_id', $lessonId)->orderBy('sort_order')->get();
    }
}