<?php

namespace App\Application\UseCase\Course;

final class CreateCourseCommand
{
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly int $category
    ) {}
}
