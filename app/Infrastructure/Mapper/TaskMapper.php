<?php

namespace App\Infrastructure\Mapper;

use App\Domain\Assessment\AllowedFileExtensions;
use App\Domain\Assessment\Points;
use App\Domain\Assessment\Question;
use App\Domain\Assessment\QuestionOption;
use App\Domain\Assessment\Task;
use App\Domain\Assessment\TaskId;
use App\Domain\Lesson\LessonId;
use App\Infrastructure\Eloquent\QuestionModel;
use App\Infrastructure\Eloquent\QuestionOptionModel;
use App\Infrastructure\Eloquent\TaskModel;

final class TaskMapper
{
    public function toEntity(TaskModel $model): Task
    {
        $questions = $model->questions->map(fn (QuestionModel $q) => Question::reconstitute(
            (int) $q->id,
            $q->question,
            $q->question_type,
            new Points((int) $q->points),
            $q->correct_answer,
            $q->options->map(fn (QuestionOptionModel $o) => new QuestionOption($o->option, (bool) $o->is_correct))->all(),
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