<?php

namespace App\Repositories;

use App\Contracts\Repositories\QuestionRepositoryInterface;
use App\Models\Question;

class QuestionRepository implements QuestionRepositoryInterface
{
    public function create(array $data): Question
    {
        return Question::create($data);
    }

    public function deleteByTaskId(int $taskId): void
    {
        Question::where('task_id', $taskId)->delete();
    }
}