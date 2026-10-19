<?php

namespace App\Domain\Lesson;


final class LessonContent
{
    private function __construct(
        private readonly LessonType $type,
        private readonly ?string $text,
        private readonly ?string $videoPath
    ) {}

    public static function of(LessonType $type, ?string $text, ?string $videoPath): self
    {
        return new self(
            $type,
            $type === LessonType::TEXT ? $text : null,
            $type === LessonType::VIDEO ? $videoPath : null
        );
    }

    public function type(): LessonType { return $this->type; }
    public function text(): ?string { return $this->text; }
    public function videoPath(): ?string { return $this->videoPath; }
}