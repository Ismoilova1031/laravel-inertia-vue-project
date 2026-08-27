<?php

namespace App\Contracts\Repositories;

interface QuestionOptionRepositoryInterface
{
    public function create(array $data): void;
}