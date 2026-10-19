<?php

namespace App\Domain\Course;

final class NewCourse
{
    private function __construct(
        public CourseTitle $title,
        public CourseDescription $description,
        public CourseCategory $category
    ) {}

    public static function draft(CourseTitle $title, CourseDescription $description, CourseCategory $category): self
    {
        return new self($title, $description, $category);
    }

    public function status(): CourseStatus
    {
        return CourseStatus::DRAFT;
    }
}
