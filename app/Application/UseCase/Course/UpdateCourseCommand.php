<?php

namespace App\Application\UseCase\Course;

final class UpdateCourseCommand
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $description,
        public readonly int $category,
        public readonly int $status
    ) {}
}
