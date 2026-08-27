<?php

namespace App\Dtos;

use Illuminate\Http\UploadedFile;
use App\Dtos\TaskDto;
class LessonDto
{
    public function __construct(
        public string $title,
        public string $description,
        public ?string $lesson_content,
        public int $lesson_type,
        public ?UploadedFile $video,
        public int $sort_order,
        public int $course_id,

        public ?TaskDto $tasks = null
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(

            title: $data['title'],
            description: $data['description'] ?? '',
            lesson_content: $data['lesson_content'] ?? null,
            lesson_type: $data['lesson_type'],
            video: $data['video'] ?? null,
            sort_order: $data['sort_order'],
            course_id: $data['course_id'],
            tasks: $data['tasks'] ?? null
        );
    }

    public function toArray(?string $videoPath = null): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'lesson_content' => $this->lesson_content,
            'lesson_type' => $this->lesson_type,
            'video_url' => $videoPath,
            'sort_order' => $this->sort_order,
            'course_id' => $this->course_id,
            'tasks' => $this->tasks,
        ];
    }
}