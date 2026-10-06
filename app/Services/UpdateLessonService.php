<?php

namespace App\Services;

use App\Contracts\Services\UpdateLessonServiceInterface;
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
use App\Models\Task;

class UpdateLessonService implements UpdateLessonServiceInterface
{
    public function __construct(
        private LessonRepositoryInterface $lessonRepository,
        private TaskRepositoryInterface $taskRepository,
        private QuestionRepositoryInterface $questionRepository,
        private QuestionOptionRepositoryInterface $questionOptionRepository
    ) {}

    public function update(Lesson $lesson, LessonDto $dto, ?string $videoPath = null): Lesson
    {
        if ($dto->lesson_type === LessonType::TASK->value) {

            $lesson->lesson_type !== LessonType::TASK ? $this->createTask($dto->tasks, $lesson->id) : $this->updateTask($lesson->task, $dto->tasks);
        }

        $lesson = $this->lessonRepository->update($lesson, $dto->toArray($videoPath));
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
            $this->createQuestion($task->questions, $createdTask->id);
        }
    }

    private function updateTask(Task $task, TaskDto $dto): void
    {
        $updatedTask = $this->taskRepository->update($task, [
            'task_type' => $dto->type->value,
            'deadline' => $dto->deadline,
            'allowed_file_extensions' => $dto->file_extensions,
        ]);

        if ($dto->type === TaskType::QUIZ) {
            $task->task_type !== TaskType::QUIZ ? $this->createQuestion($dto->questions, $updatedTask->id) : $this->createQuestion($dto->questions, $updatedTask->id, true);
        }
    }

    private function createQuestion(array $questions, int $taskId, bool $isUpdate = false): void
    {
        if ($isUpdate) {
            $this->questionRepository->deleteByTaskId($taskId);
        }
        foreach ($questions as $index => $question) {
            $createdQuestion = $this->questionRepository->create([
                'task_id' => $taskId,
                'question' => $question['question'],
                'question_type' => QuestionType::from($question['question_type'])->value,
                'points' => $question['points'],
                'sort_order' => $index + 1,
                'correct_answer' => $question['correct_answer'],
            ]);
            if ($question['question_type'] === QuestionType::MULTIPLE_SELECT->value || $question['question_type'] === QuestionType::SINGLE_CHOICE->value) {
                $this->createQuestionOptions($question['options'], $createdQuestion->id);
            }
        }
    }

    private function createQuestionOptions(array $options, int $questionId): void
    {
        $this->questionOptionRepository->deleteByQuestionId($questionId);
        foreach ($options as $option) {
            $this->questionOptionRepository->create([
                'question_id' => $questionId,
                'option' => $option['option'],
                'is_correct' => $option['is_correct'],
            ]);
        }
    }
}
