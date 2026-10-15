<?php

namespace App\Contracts\UseCases;

use App\Dtos\LessonDto;
use App\Infrastructure\Eloquent\LessonModel;

interface CreateLessonUseCaseInterface
{
    public function execute(LessonDto $lessonDto): LessonModel;
}
