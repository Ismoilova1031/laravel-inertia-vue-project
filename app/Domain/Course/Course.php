<?php

namespace App\Domain\Course;

use App\Domain\Course\Exception\CannotDeleteNonDraftCourseException;
use App\Domain\Course\Exception\CannotPublishWithoutLessonsException;

final class Course
{
    private function __construct(
        private CourseId $id,
        private CourseTitle $title,
        private CourseDescription $description,
        private CourseCategory $category,
        private CourseStatus $status,
    ) {}

    public static function reconstitute(
        CourseId $id,
        CourseTitle $title,
        CourseDescription $description,
        CourseCategory $category,
        CourseStatus $status
    ): self {
        return new self($id, $title, $description, $category, $status);
    }

    public function changeDetails(CourseTitle $title, CourseDescription $description, CourseCategory $category): void
    {
        $this->title = $title;
        $this->description = $description;
        $this->category = $category;
    }

    public function changeStatus(CourseStatus $target, int $lessonCount): void
    {
        if ($target === $this->status) {
            return;
        }

        if ($target === CourseStatus::PUBLISHED && $lessonCount < 1) {
            throw CannotPublishWithoutLessonsException::create();
        }

        $this->status = $target;
    }

    public function isPublished(): bool
    {
        return $this->status === CourseStatus::PUBLISHED;
    }

    public function id(): CourseId
    {
        return $this->id;
    }

    public function title(): CourseTitle
    {
        return $this->title;
    }

    public function description(): CourseDescription
    {
        return $this->description;
    }

    public function category(): CourseCategory
    {
        return $this->category;
    }

    public function status(): CourseStatus
    {
        return $this->status;
    }

    public function assertDeletable(): void
    {
        if ($this->status !== CourseStatus::DRAFT) {
            throw CannotDeleteNonDraftCourseException::create();
        }
    }
}
