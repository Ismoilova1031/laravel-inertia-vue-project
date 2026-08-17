<?php

namespace App\Dtos;

class CourseDto
{
    public function __construct(
        public string $title,
        public string $description,
        public int $category,
        public int $status,
    ) {
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category,
            'status' => $this->status,
        ];
    }
}