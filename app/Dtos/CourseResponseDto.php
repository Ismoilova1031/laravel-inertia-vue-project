<?php

namespace App\Dtos;

use App\Models\Course;

class CourseResponseDto
{
    public function __construct(
        public int $id,
        public string $title,
        public string $description,
        public string $category,
        public string $status,
    ) {}

    public static function fromModel(Course $course): self
    {
        return new self(
            id: $course->id,
            title: $course->title,
            description: $course->description,
            category: $course->category->label(),
            status: $course->status->label(),
        );
    }
}