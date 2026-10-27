<?php

namespace App\Domain\Assessment\VO;

use App\Domain\Assessment\Exception\InvalidTaskException;
use App\Domain\Assessment\NewQuestion;
use App\Domain\Assessment\TaskType;
use App\Domain\Assessment\AllowedFileExtensions;

final class TaskContent
{
    private function __construct(
        private readonly TaskType $type,
        private readonly ?AllowedFileExtensions $extensions,
        private readonly array $questions,
    ) {}

    public static function of(TaskType $type, ?AllowedFileExtensions $extensions, array $questions): self
    {
        if ($type === TaskType::QUIZ && count($questions) < 1) {
            throw InvalidTaskException::because('Quiz kamida bitta savol talab qiladi.');
        }

        if ($type === TaskType::FILE && $extensions === null) {
            throw InvalidTaskException::because('Fayl topshirig\'i uchun kamida bitta kengaytma tanlang.');
        }

        return new self(
            $type,
            $type === TaskType::FILE ? $extensions : null,
            $type === TaskType::QUIZ ? array_values($questions) : [],
        );
    }

    public function signature(): array
    {
        return array_map(fn (NewQuestion $q) => $q->signature(), $this->questions);
    }

    public function type(): TaskType
    {
        return $this->type;
    }

    public function allowedExtensions(): ?AllowedFileExtensions
    {
        return $this->extensions;
    }

    public function questions(): array
    {
        return $this->questions;
    }
}
