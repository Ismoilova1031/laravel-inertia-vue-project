<?php

namespace App\Domain\Assessment;

use App\Domain\Assessment\Exception\TaskLockedException;
use App\Domain\Assessment\VO\TaskContent;
use App\Domain\Lesson\VO\LessonId;
use App\Domain\Assessment\VO\TaskId;

final class Task
{
    private ?array $replacementQuestions = null;

    private function __construct(
        private readonly TaskId $id,
        private readonly LessonId $lessonId,
        private TaskType $type,
        private ?AllowedFileExtensions $allowedExtensions,
        private array $questions,
    ) {}

    public static function reconstitute(
        TaskId $id,
        LessonId $lessonId,
        TaskType $type,
        ?AllowedFileExtensions $extensions,
        array $questions,
    ): self {
        return new self($id, $lessonId, $type, $extensions, array_values($questions));
    }

    public function revise(TaskContent $content, bool $hasSubmissions): void
    {
        $structureChanged = $content->type() !== $this->type
            || $content->signature() !== $this->signature();

        if ($structureChanged && $hasSubmissions) {
            throw TaskLockedException::create();
        }

        $this->allowedExtensions = $content->allowedExtensions();

        if ($structureChanged) {
            $this->type = $content->type();
            $this->replacementQuestions = $content->questions();
        }
    }

    private function signature(): array
    {
        return array_map(fn (Question $q) => $q->signature(), $this->questions);
    }

    public function acceptsExtension(string $extension): bool
    {
        $ext = strtolower(ltrim($extension, '.'));

        return $this->allowedExtensions !== null && in_array($ext, $this->allowedExtensions->values(), true);
    }

    public function totalPoints(): int
    {
        return array_sum(array_map(fn (Question $q) => $q->points()->value(), $this->questions));
    }

    public function questionReplacement(): ?array
    {
        return $this->replacementQuestions;
    }

    public function id(): TaskId
    {
        return $this->id;
    }

    public function lessonId(): LessonId
    {
        return $this->lessonId;
    }

    public function type(): TaskType
    {
        return $this->type;
    }

    public function allowedExtensions(): ?AllowedFileExtensions
    {
        return $this->allowedExtensions;
    }

    public function questions(): array
    {
        return $this->questions;
    }
}
