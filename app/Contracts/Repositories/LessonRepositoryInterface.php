<?php

namespace App\Contracts\Repositories;

use App\Models\Lesson;

interface LessonRepositoryInterface
{
    public function create(array $data): Lesson;

    public function update(int $id, array $data): bool;

    public function delete(Lesson $lesson): bool;

}