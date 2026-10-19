<?php

namespace App\Domain\Assessment;

final class QuestionOption
{
    public function __construct(
        private readonly int $id,
        private readonly string $text,
        private readonly bool $isCorrect,
    ) {}

    public function id(): int
    {
        return $this->id;
    }

    public function text(): string
    {
        return $this->text;
    }

    public function isCorrect(): bool
    {
        return $this->isCorrect;
    }
}
