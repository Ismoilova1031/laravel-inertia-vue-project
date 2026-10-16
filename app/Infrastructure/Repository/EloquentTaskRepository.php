<?php

namespace App\Infrastructure\Repository;

use App\Domain\Assessment\Task;
use App\Domain\Assessment\TaskRepositoryInterface;
use App\Domain\Lesson\LessonId;
use App\Infrastructure\Eloquent\QuestionModel;
use App\Infrastructure\Eloquent\QuestionOptionModel;
use App\Infrastructure\Eloquent\TaskModel;
use App\Infrastructure\Mapper\TaskMapper;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class EloquentTaskRepository implements TaskRepositoryInterface
{
    public function __construct(private readonly TaskMapper $mapper) {}

    public function findByLessonId(LessonId $lessonId): ?Task
    {
        $model = TaskModel::with('questions.options')
            ->where('lesson_id', $lessonId->value())
            ->first();

        return $model ? $this->mapper->toEntity($model) : null;
    }

    public function save(Task $task): Task
    {
        return DB::transaction(function () use ($task) {
            $attributes = [
                'lesson_id' => $task->lessonId()->value(),
                'task_type' => $task->type(),
                'allowed_file_extensions' => $task->allowedExtensions()?->values(),
            ];

            if ($task->id() === null) {
                $model = TaskModel::create($attributes);
            } else {
                $model = TaskModel::find($task->id()->value())
                    ?? throw new RuntimeException('Task topilmadi.');
                $model->update($attributes);
            }

            if ($task->questionsChanged()) {
                $this->replaceQuestions($model, $task);
            }

            return $this->mapper->toEntity(TaskModel::with('questions.options')->findOrFail($model->id));
        });
    }

    public function deleteByLessonId(LessonId $lessonId): void
    {
        TaskModel::where('lesson_id', $lessonId->value())->delete();
    }

    private function replaceQuestions(TaskModel $model, Task $task): void
    {
        QuestionModel::where('task_id', $model->id)->delete();  

        $now = now();

        foreach ($task->questions() as $index => $question) {
            $questionModel = QuestionModel::create([
                'task_id' => $model->id,
                'question' => $question->text(),
                'question_type' => $question->type(),
                'points' => $question->points()->value(),
                'sort_order' => $index + 1,
                'correct_answer' => $question->correctAnswer(),
            ]);

            $rows = array_map(fn ($option) => [
                'question_id' => $questionModel->id,
                'option' => $option->text(),
                'is_correct' => $option->isCorrect(),
                'created_at' => $now,
                'updated_at' => $now,
            ], $question->options());

            if ($rows !== []) {
                QuestionOptionModel::insert($rows);
            }
        }
    }
}