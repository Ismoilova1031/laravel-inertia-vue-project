<?php

namespace App\Infrastructure\Repository;

use App\Domain\Assessment\Exception\TaskNotFoundException;
use App\Domain\Assessment\NewQuestion;
use App\Domain\Assessment\NewTask;
use App\Domain\Assessment\Task;
use App\Domain\Assessment\TaskId;
use App\Domain\Assessment\TaskRepositoryInterface;
use App\Domain\Lesson\LessonId;
use App\Infrastructure\Eloquent\QuestionModel;
use App\Infrastructure\Eloquent\QuestionOptionModel;
use App\Infrastructure\Eloquent\TaskModel;
use App\Infrastructure\Mapper\TaskMapper;
use Illuminate\Support\Facades\DB;

final class EloquentTaskRepository implements TaskRepositoryInterface
{
    public function __construct(private readonly TaskMapper $mapper) {}

    public function findById(TaskId $id): ?Task
    {
        $model = TaskModel::with('questions.options')->find($id->value());

        return $model ? $this->mapper->toEntity($model) : null;
    }

    public function findByLessonId(LessonId $lessonId): ?Task
    {
        $model = TaskModel::with('questions.options')->where('lesson_id', $lessonId->value())->first();

        return $model ? $this->mapper->toEntity($model) : null;
    }

    public function create(NewTask $new): Task
    {
        return DB::transaction(function () use ($new) {
            $model = TaskModel::create([
                'lesson_id' => $new->lessonId->value(),
                'task_type' => $new->content->type(),
                'allowed_file_extensions' => $new->content->allowedExtensions()?->values(),
            ]);

            $this->insertQuestions((int) $model->id, $new->content->questions());

            return $this->mapper->toEntity(TaskModel::with('questions.options')->find($model->id));
        });
    }

    public function save(Task $task): void
    {
        DB::transaction(function () use ($task) {
            $model = TaskModel::find($task->id()->value())
                ?? throw TaskNotFoundException::withId($task->id());

            $model->update([
                'task_type' => $task->type(),
                'allowed_file_extensions' => $task->allowedExtensions()?->values(),
            ]);

            $replacement = $task->questionReplacement();

            if ($replacement !== null) {
                QuestionModel::where('task_id', $model->id)->delete();
                $this->insertQuestions((int) $model->id, $replacement);
            }
        });
    }

    public function deleteByLessonId(LessonId $lessonId): void
    {
        TaskModel::where('lesson_id', $lessonId->value())->delete();
    }

    /** @param list<NewQuestion> $questions */
    private function insertQuestions(int $taskId, array $questions): void
    {
        $now = now();

        foreach ($questions as $index => $question) {
            $questionModel = QuestionModel::create([
                'task_id' => $taskId,
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
