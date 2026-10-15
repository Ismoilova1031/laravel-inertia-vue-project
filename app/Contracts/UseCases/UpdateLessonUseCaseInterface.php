<?php

namespace App\Contracts\UseCases;

use App\Dtos\LessonDto;
use App\Infrastructure\Eloquent\LessonModel;

interface UpdateLessonUseCaseInterface
{
    public function execute(LessonModel $lesson, LessonDto $dto): LessonModel;
}
