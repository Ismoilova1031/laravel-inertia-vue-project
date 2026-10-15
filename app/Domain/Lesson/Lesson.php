<?php

namespace App\Domain\Lesson;

use App\Domain\Course\CourseId;

final class Lesson
{
    private function __construct(
        private ?LessonId $id,
        private readonly CourseId $courseId,
        private LessonTitle $title,
        private LessonDescription $description,
        private LessonType $type,
        private ?string $content,
        private ?string $videoPath,
        private SortOrder $sortOrder,
    ) {}

    public static function create(
        CourseId $courseId,
        LessonTitle $title,
        LessonDescription $description,
        LessonType $type,
        SortOrder $sortOrder,
        ?string $content = null,
        ?string $videoPath = null,
    ): self {
        $lesson = new self(null, $courseId, $title, $description, $type, null, null, $sortOrder);
        $lesson->applyContent($type, $content, $videoPath);

        return $lesson;
    }

    public static function reconstitute(
        LessonId $id,
        CourseId $courseId,
        LessonTitle $title,
        LessonDescription $description,
        LessonType $type,
        ?string $content,
        ?string $videoPath,
        SortOrder $sortOrder,
    ): self {
        return new self($id, $courseId, $title, $description, $type, $content, $videoPath, $sortOrder);
    }

    public function revise(
        LessonTitle $title,
        LessonDescription $description,
        LessonType $type,
        ?string $content,
        ?string $newVideoPath,
    ): void {
        $this->title = $title;
        $this->description = $description;
        $this->applyContent($type, $content, $newVideoPath);
    }

    public function moveTo(SortOrder $sortOrder): void
    {
        $this->sortOrder = $sortOrder;
    }

    private function applyContent(LessonType $type, ?string $content, ?string $videoPath): void
    {
        $this->type = $type;
        $this->content = $type === LessonType::TEXT ? $content : null;
        $this->videoPath = $type === LessonType::VIDEO ? ($videoPath ?? $this->videoPath) : null;
    }

    public function id(): ?LessonId { return $this->id; }
    public function courseId(): CourseId { return $this->courseId; }
    public function title(): LessonTitle { return $this->title; }
    public function description(): LessonDescription { return $this->description; }
    public function type(): LessonType { return $this->type; }
    public function content(): ?string { return $this->content; }
    public function videoPath(): ?string { return $this->videoPath; }
    public function sortOrder(): SortOrder { return $this->sortOrder; }
}