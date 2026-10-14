<?php

namespace App\Contracts\UseCases;

use App\Dtos\LessonDto;
use App\Models\Lesson;

interface UpdateLessonUseCaseInterface
{
    public function execute(Lesson $lesson, LessonDto $dto): Lesson;
}
