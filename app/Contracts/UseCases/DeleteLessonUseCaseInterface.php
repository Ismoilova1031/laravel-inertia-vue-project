<?php

namespace App\Contracts\UseCases;

use App\Models\Lesson;

interface DeleteLessonUseCaseInterface
{
    public function execute(Lesson $lesson): void;
}