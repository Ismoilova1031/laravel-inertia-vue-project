<?php

namespace App\Dtos;

use App\Models\Course;
use App\Models\Lesson;
use App\Dtos\LessonResponseDto;

class CourseResponseDto
{
    public function __construct(
        public int $id,
        public string $title,
        public string $description,
        public string $category,
        public string $status,
        public array $lessons = [],
        public array $students = [],
    ) {}

    public static function fromModel(Course $course): self
    {
        return new self(
            id: $course->id,
            title: $course->title,
            description: $course->description,
            category: $course->category->label(),
            status: $course->status->label(),
            lessons: $course->lessons->map(
                fn (Lesson $lesson) => LessonResponseDto::fromModel($lesson)
            )->toArray(),
            students: $course->students->map(
                fn ($student) => StudentResponseDto::fromModel($student)
            )->toArray(),
        );
    }
}