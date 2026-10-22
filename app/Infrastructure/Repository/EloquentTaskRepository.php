<?php

namespace App\Infrastructure\Repository;

use App\Domain\Assessment\AllowedFileExtensions;
use App\Domain\Assessment\Exception\TaskNotFoundException;
use App\Domain\Assessment\NewQuestion;
use App\Domain\Assessment\NewTask;
use App\Domain\Assessment\Points;
use App\Domain\Assessment\Question;
use App\Domain\Assessment\QuestionOption;
use App\Domain\Assessment\Task;
use App\Domain\Assessment\TaskId;
use App\Domain\Assessment\TaskRepositoryInterface;
use App\Domain\Course\CourseId;
use App\Domain\Lesson\LessonId;
use App\Infrastructure\Eloquent\QuestionModel;
use App\Infrastructure\Eloquent\QuestionOptionModel;
use App\Infrastructure\Eloquent\TaskModel;
use Illuminate\Support\Facades\DB;

final class EloquentTaskRepository implements TaskRepositoryInterface
{
    public function findById(TaskId $id): ?Task
    {
        $model = TaskModel::with('questions.options')->find($id->value());

        return $model ? $this->toEntity($model) : null;
    }

    public function findByLessonId(LessonId $lessonId): ?Task
    {
        $model = TaskModel::with('questions.options')->where('lesson_id', $lessonId->value())->first();

        return $model ? $this->toEntity($model) : null;
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

            return $this->toEntity(TaskModel::with('questions.options')->find($model->id));
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

    public function courseIdOf(TaskId $taskId): ?CourseId
    {
        $id = TaskModel::query()
            ->join('lessons', 'lessons.id', '=', 'tasks.lesson_id')
            ->where('tasks.id', $taskId->value())
            ->value('lessons.course_id');

        return $id !== null ? new CourseId((int) $id) : null;
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

    private function toEntity(TaskModel $model): Task
    {
        $questions = $model->questions->map(fn (QuestionModel $q) => Question::reconstitute(
            (int) $q->id,
            $q->question,
            $q->question_type,
            new Points((int) $q->points),
            $q->correct_answer,
            $q->options->map(fn (QuestionOptionModel $o) => new QuestionOption(
                (int) $o->id,
                $o->option,
                (bool) $o->is_correct
            ))->all(),
        ))->all();

        return Task::reconstitute(
            new TaskId((int) $model->id),
            new LessonId((int) $model->lesson_id),
            $model->task_type,
            $model->allowed_file_extensions
                ? AllowedFileExtensions::fromStrings($model->allowed_file_extensions)
                : null,
            $questions,
        );
    }
}
