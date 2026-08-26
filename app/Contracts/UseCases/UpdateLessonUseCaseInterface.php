<?php

namespace App\Contracts\UseCases;

use App\Models\Lesson;
use App\Dtos\LessonDto;

interface UpdateLessonUseCaseInterface
{
    public function execute(Lesson $lesson, LessonDto $dto): Lesson;
}