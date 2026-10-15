<?php

namespace App\Contracts\Repositories;

use App\Infrastructure\Eloquent\LessonModel;
use Illuminate\Support\Collection;

interface LessonRepositoryInterface
{
    public function create(array $data): LessonModel;

    public function update(LessonModel $lesson, array $data): LessonModel;

    public function updateById(int $id, array $data): LessonModel;

    public function delete(LessonModel $lesson): bool;

    public function getByCourseId(int $courseId): Collection;
}
