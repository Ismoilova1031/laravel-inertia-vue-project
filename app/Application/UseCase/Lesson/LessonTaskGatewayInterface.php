<?php

namespace App\Application\UseCase\Lesson;

use App\Dtos\TaskDto;

interface LessonTaskGatewayInterface
{
    public function replaceFor(int $lessonId, TaskDto $task): void;

    public function deleteFor(int $lessonId): void;
}