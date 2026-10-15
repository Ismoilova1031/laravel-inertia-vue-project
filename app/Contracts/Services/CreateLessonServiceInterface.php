<?php

namespace App\Contracts\Services;

use App\Dtos\LessonDto;
use App\Infrastructure\Eloquent\LessonModel;

interface CreateLessonServiceInterface
{
    public function create(LessonDto $dto, ?string $videoPath): LessonModel;
}
