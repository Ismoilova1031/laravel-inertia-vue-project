<?php

namespace App\Domain\Lesson;

use App\Domain\Course\VO\CourseId;
use App\Domain\Lesson\VO\LessonDescription;
use App\Domain\Lesson\VO\LessonId;
use App\Domain\Lesson\VO\LessonTitle;
use App\Domain\Lesson\VO\SortOrder;

final class Lesson
{
    private function __construct(
        private LessonId $id,
        private readonly CourseId $courseId,
        private LessonTitle $title,
        private LessonDescription $description,
        private LessonContent $content,
        private SortOrder $sortOrder,
    ) {}

    public static function reconstitute(
        LessonId $id,
        CourseId $courseId,
        LessonTitle $title,
        LessonDescription $description,
        LessonContent $content,
        SortOrder $sortOrder,
    ): self {
        return new self($id, $courseId, $title, $description, $content, $sortOrder);
    }

    public function revise(
        LessonTitle $title,
        LessonDescription $description,
        LessonType $type,
        ?string $text,
        ?string $newVideoPath,
    ): void {
        $this->title = $title;
        $this->description = $description;
        $this->content = LessonContent::of($type, $text, $newVideoPath ?? $this->content->videoPath());
    }

    public function moveTo(SortOrder $sortOrder): void
    {
        $this->sortOrder = $sortOrder;
    }

    public function id(): LessonId
    {
        return $this->id;
    }

    public function courseId(): CourseId
    {
        return $this->courseId;
    }

    public function title(): LessonTitle
    {
        return $this->title;
    }

    public function description(): LessonDescription
    {
        return $this->description;
    }

    public function type(): LessonType
    {
        return $this->content->type();
    }

    public function content(): ?string
    {
        return $this->content->text();
    }

    public function videoPath(): ?string
    {
        return $this->content->videoPath();
    }

    public function sortOrder(): SortOrder
    {
        return $this->sortOrder;
    }
}
