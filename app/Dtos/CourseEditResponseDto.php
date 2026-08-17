<?php

namespace App\Dtos;

class CourseEditResponseDto
{
    public function __construct(
        public int $id,
        public string $title,
        public string $description,
        public array $category,
        public array $status,
    ) {
    }

    public static function fromModel($course): self
    {
        return new self(
            id: $course->id,
            title: $course->title,
            description: $course->description,
            category: [
                'value' => $course->category->value,
                'label' => $course->category->label(),
            ],
            status: [
                'value' => $course->status->value,
                'label' => $course->status->label(),
            ],
        );
    }
}