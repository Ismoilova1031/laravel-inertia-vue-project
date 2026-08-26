<?php

namespace App\Contracts\Repositories;

use App\Models\Lesson;
use Illuminate\Support\Collection;

interface LessonRepositoryInterface
{
    public function create(array $data): Lesson;

    public function update(Lesson $lesson, array $data): Lesson;

    public function updateById(int $id, array $data): Lesson;

    public function delete(Lesson $lesson): bool;

    public function getByCourseId(int $courseId): Collection;

}