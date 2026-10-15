<?php

namespace App\Contracts\Services;

use App\Dtos\LessonDto;
use App\Infrastructure\Eloquent\LessonModel;

interface UpdateLessonServiceInterface
{
    public function update(LessonModel $lesson, LessonDto $dto, ?string $videoPath = null): LessonModel;
}
