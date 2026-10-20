<?php

namespace App\Domain\Assessment\Submission;

final class StudentAnswer
{
    public function __construct(
        private readonly int $questionId,
        private readonly array $selectesOptionIds,
        private readonly ?string $text,
        private ?int $score,
        private ?string $teacherComment = null,
    ) {}

    public function grade(int $score, ?string $comment): void
    {
        $this->score = $score;
        $this->teacherComment = $comment;
    }

    public function isGraded(): bool { return $this->score !== null; }
    public function questionId(): int { return $this->questionId; }
    public function selectesOptionIds(): array { return $this->selectesOptionIds; }
    public function text(): ?string { return $this->text; }
    public function score(): ?int { return $this->score; }
    public function teacherComment(): ?string { return $this->teacherComment; }
}