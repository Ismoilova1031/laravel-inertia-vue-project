<?php

namespace App\Contracts\Repositories;

use App\Models\Question;

interface QuestionRepositoryInterface
{
    public function create(array $data): Question;

    public function deleteByTaskId(int $taskId): void;
}