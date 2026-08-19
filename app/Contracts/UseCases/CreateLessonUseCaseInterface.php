<?php

namespace App\Contracts\UseCases;

use App\Dtos\LessonDto;
use App\Models\Lesson;

interface CreateLessonUseCaseInterface
{
    public function execute(LessonDto $lessonDto): Lesson;
}