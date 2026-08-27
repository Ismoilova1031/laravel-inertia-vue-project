<?php

namespace App\Repositories;

use App\Contracts\Repositories\QuestionOptionRepositoryInterface;
use App\Models\QuestionOption;

class QuestionOptionRepository implements QuestionOptionRepositoryInterface
{
    public function create(array $data): void
    {
        QuestionOption::create($data);
    }
}