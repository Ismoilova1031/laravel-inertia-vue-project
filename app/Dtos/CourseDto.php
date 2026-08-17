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

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            description: $data['description'],
            category: $data['category'],
            status: $data['status'],
        );
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