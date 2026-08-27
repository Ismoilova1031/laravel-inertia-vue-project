<?php

namespace App\Services;

use App\Contracts\Services\CreateLessonServiceInterface;
use App\Contracts\Repositories\LessonRepositoryInterface;
use App\Contracts\Repositories\TaskRepositoryInterface;
use App\Dtos\LessonDto;
use App\Dtos\TaskDto;
use App\Models\Lesson;
use App\Enums\LessonType;
use App\Enums\TaskType;

class CreateLessonService implements CreateLessonServiceInterface
{
    public function __construct(
        private LessonRepositoryInterface $lessonRepository,
        private TaskRepositoryInterface $taskRepository
    ) {}

    public function create(LessonDto $dto, ?string $videoPath): Lesson
    {
        $lesson =  $this->lessonRepository->create($dto->toArray($videoPath));

        if ($dto->lesson_type === LessonType::TASK->value) {

            $this->createTask($dto->tasks, $lesson->id);
        }

        return $lesson;
    }

    private function createTask(TaskDto $task, int $lessonId): void
    {
        if ($task->type !== TaskType::QUIZ) {
            $this->taskRepository->create([
                'lesson_id' => $lessonId,
                'task_type' => $task->type->value,
                'deadline' => $task->deadline,
                'allowed_file_extensions' => $task->file_extensions,
                'questions' => null
            ]);
        }
    }
}
