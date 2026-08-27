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
}