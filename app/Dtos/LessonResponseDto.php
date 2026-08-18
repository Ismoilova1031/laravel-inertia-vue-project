<?php

namespace App\Dtos;

use App\Models\Lesson;

class LessonResponseDto
{
    public function __construct(
        public int $id,
        public string $title,
        public string $description,
        public int $lessonType,
        public ?string $lessonContent,
        public ?string $videoUrl,
        public int $sortOrder,
    ) {}

    public static function fromModel(Lesson $lesson): self
    {
        return new self(
            id: $lesson->id,
            title: $lesson->title,
            description: $lesson->description,
            lessonType: $lesson->lesson_type->value,
            lessonContent: $lesson->lesson_content,
            videoUrl: $lesson->video_url,
            sortOrder: $lesson->sort_order,
        );
    }
}