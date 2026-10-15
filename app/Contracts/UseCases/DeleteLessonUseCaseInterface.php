<?php

namespace App\Contracts\UseCases;

use App\Infrastructure\Eloquent\LessonModel;

interface DeleteLessonUseCaseInterface
{
    public function execute(LessonModel $lesson): void;
}
