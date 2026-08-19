<?php

namespace App\Contracts\Repositories;

use App\Models\Lesson;

interface LessonRepositoryInterface
{
    public function create(array $data): Lesson;
}