<?php

namespace App\Dtos;

class CourseDto
{
    public function __construct(
        public string $title,
        public string $description,
    ) {
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
        ];
    }
}