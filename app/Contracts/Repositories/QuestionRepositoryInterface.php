<?php

namespace App\Contracts\Repositories;

use App\Models\Question;

interface QuestionRepositoryInterface
{
    public function create(array $data): Question;
}