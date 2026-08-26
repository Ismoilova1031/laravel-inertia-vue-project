<?php

namespace App\Contracts\Repositories;

use App\Models\Lesson;
use Illuminate\Support\Collection;

interface LessonRepositoryInterface
{
    public function create(array $data): Lesson;

    public function update(int $id, array $data): bool;

    public function delete(Lesson $lesson): bool;

    public function getByCourseId(int $courseId): Collection;

}