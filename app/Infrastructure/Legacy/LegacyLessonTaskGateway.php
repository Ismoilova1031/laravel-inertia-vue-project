<?php
// app/Infrastructure/Legacy/LegacyLessonTaskGateway.php
declare(strict_types=1);

namespace App\Infrastructure\Legacy;

use App\Application\UseCase\Lesson\LessonTaskGatewayInterface;
use App\Contracts\Repositories\QuestionOptionRepositoryInterface;
use App\Contracts\Repositories\QuestionRepositoryInterface;
use App\Contracts\Repositories\TaskRepositoryInterface;
use App\Dtos\TaskDto;
use App\Enums\QuestionType;
use App\Enums\TaskType;
use App\Models\Task;

/** VAQTINCHA: Assessment DDD'ga o'tganda butun fayl o'chadi. */
final class LegacyLessonTaskGateway implements LessonTaskGatewayInterface
{
    public function __construct(
        private readonly TaskRepositoryInterface $tasks,
        private readonly QuestionRepositoryInterface $questions,
        private readonly QuestionOptionRepositoryInterface $options,
    ) {}

    public function replaceFor(int $lessonId, TaskDto $task): void
    {
        $data = [
            'task_type'               => $task->type->value,
            'deadline'                => $task->deadline,
            'allowed_file_extensions' => $task->file_extensions,
        ];

        $existing = Task::query()->where('lesson_id', $lessonId)->first();

        if ($existing !== null) {
            $this->tasks->update($existing, $data);
            $taskId = $existing->id;
            $this->questions->deleteByTaskId($taskId);
        } else {
            $taskId = $this->tasks->create(['lesson_id' => $lessonId] + $data)->id;
        }

        if ($task->type === TaskType::QUIZ) {
            $this->createQuestions($task->questions ?? [], $taskId);
        }
    }

    public function deleteFor(int $lessonId): void
    {
        $this->tasks->deleteByLessonId($lessonId);
    }

    private function createQuestions(array $questions, int $taskId): void
    {
        foreach ($questions as $index => $question) {
            $type = QuestionType::from($question['question_type']);

            $created = $this->questions->create([
                'task_id'        => $taskId,
                'question'       => $question['question'],
                'question_type'  => $type->value,
                'points'         => $question['points'],
                'sort_order'     => $index + 1,
                'correct_answer' => $question['correct_answer'] ?? null,
            ]);

            if ($type === QuestionType::MULTIPLE_SELECT || $type === QuestionType::SINGLE_CHOICE) {
                foreach ($question['options'] ?? [] as $option) {
                    $this->options->create([
                        'question_id' => $created->id,
                        'option'      => $option['option'],
                        'is_correct'  => $option['is_correct'],
                    ]);
                }
            }
        }
    }
}