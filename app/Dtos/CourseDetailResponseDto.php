<?php

namespace App\Dtos;

use App\Models\Lesson;
use App\Models\Student;
use App\Dtos\LessonResponseDto;
use App\Dtos\StudentResponseDto;

class CourseDetailResponseDto
{
    public function __construct(
        public int $id,
        public string $title,
        public string $description,
        public array $category,
        public array $status,
        public array $lessons = [],
        public array $students = [],
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
            lessons: $course->lessons->map(
                fn (Lesson $lesson) => LessonResponseDto::fromModel($lesson)
            )->toArray(),
            students: $course->students->map(
                fn (Student $student) => StudentResponseDto::fromModel($student)
            )->toArray(),
        );
    }
}