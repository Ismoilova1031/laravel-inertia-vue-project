<?php

namespace App\Services;

use App\Contracts\Services\CreateLessonServiceInterface;
use App\Contracts\Repositories\LessonRepositoryInterface;
use App\Contracts\Repositories\TaskRepositoryInterface;
use App\Contracts\Repositories\QuestionRepositoryInterface;
use App\Contracts\Repositories\QuestionOptionRepositoryInterface;
use App\Dtos\LessonDto;
use App\Dtos\TaskDto;
use App\Models\Lesson;
use App\Enums\LessonType;
use App\Enums\TaskType;
use App\Enums\QuestionType;

class CreateLessonService implements CreateLessonServiceInterface
{
    public function __construct(
        private LessonRepositoryInterface $lessonRepository,
        private TaskRepositoryInterface $taskRepository,
        private QuestionRepositoryInterface $questionRepository,
        private QuestionOptionRepositoryInterface $questionOptionRepository
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
        $createdTask = $this->taskRepository->create([
                'lesson_id' => $lessonId,
                'task_type' => $task->type->value,
                'deadline' => $task->deadline,
                'allowed_file_extensions' => $task->file_extensions,
            ]);

        if ($task->type === TaskType::QUIZ) {
            $createdTask = $this->taskRepository->create([
                'lesson_id' => $lessonId,
                'task_type' => $task->type->value,
                'deadline' => $task->deadline,
            ]);

            $this->createQuestion($task->questions, $createdTask->id);
        }
    }

    private function createQuestion(array $questions, int $taskId): void
    {
        foreach ($questions as $question) {
            $createdQuestion = $this->questionRepository->create([
                'task_id' => $taskId,
                'question' => $question['question'],
                'question_type' => QuestionType::from($question['type'])->value,
                'points' => $question['points'],
                'sort_order' => $question['sort_order'] ?? 1,
                'correct_answer' => $question['correct_answer'],
            ]);
            if ($question['type'] === QuestionType::MULTIPLE_SELECT->value || $question['type'] === QuestionType::SINGLE_CHOICE->value) {
                $this->createQuestionOptions($question['options'], $createdQuestion->id);
            }
        }
    }

    private function createQuestionOptions(array $options, int $questionId): void
    {
        foreach ($options['options'] as $option) {
            $this->questionOptionRepository->create([
                'question_id' => $questionId,
                'option' => $option['text'],
                'is_correct' => $option['is_correct'],
            ]);
        }
    }
}
