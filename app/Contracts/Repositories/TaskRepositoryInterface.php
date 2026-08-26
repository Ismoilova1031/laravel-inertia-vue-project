<?php

namespace App\Contracts\Repositories;

use App\Models\Task;
use Illuminate\Support\Collection;

interface TaskRepositoryInterface
{
    public function create(array $data): Task;

    public function update(Task $task, array $data): Task;

    public function deleteByLessonId(int $lessonId): bool;

    public function getByLessonId(int $lessonId): Collection;
}