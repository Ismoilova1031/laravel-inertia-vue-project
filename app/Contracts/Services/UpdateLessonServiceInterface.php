<?php

namespace App\Contracts\Services;

use App\Dtos\LessonDto;
use App\Models\Lesson;

interface UpdateLessonServiceInterface
{
    public function update(Lesson $lesson, LessonDto $dto, ?string $videoPath = null): Lesson;
}