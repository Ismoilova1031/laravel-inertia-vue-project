<?php

namespace App\Contracts\UseCases;

interface ReorderLessonsUseCaseInterface
{
    public function execute(array $lessons): void;
}