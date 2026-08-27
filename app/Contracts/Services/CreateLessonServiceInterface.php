<?php

namespace App\Contracts\Services;

use App\Dtos\LessonDto;
use App\Models\Lesson;

interface CreateLessonServiceInterface
{
    public function create(LessonDto $dto, ?string $videoPath): Lesson;
}