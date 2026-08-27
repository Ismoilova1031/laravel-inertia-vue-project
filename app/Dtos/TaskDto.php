<?php

namespace App\Dtos;

use App\Enums\TaskType;

class TaskDto
{
    public function __construct(
        public TaskType $type,
        public string $deadline,
        public ?array $file_extensions = null,
        public ?array $questions = null
    ) {}
}