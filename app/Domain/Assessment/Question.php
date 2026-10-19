<?php

namespace App\Domain\Assessment;

final class Question
{
    private function __construct(
        private readonly int $id,
        private readonly string $text,
        private readonly QuestionType $type,
        private readonly Points $points,
        private readonly ?string $correctAnswer,
        private readonly array $options,
    ) {}

    public static function reconstitute(
        int $id,
        string $text,
        QuestionType $type,
        Points $points,
        ?string $correctAnswer,
        array $options
    ): self {
        return new self($id, $text, $type, $points, $correctAnswer, array_values($options));
    }

    public function signature(): array
    {
        return [
            $this->text,
            $this->type->value,
            $this->points->value(),
            $this->correctAnswer,
            array_map(fn (QuestionOption $o) => [$o->text(), $o->isCorrect()], $this->options),
        ];
    }

    public function optionIds(): array
    {
        return array_map(fn (QuestionOption $o) => $o->id(), $this->options);
    }

    public function correctOptionIds(): array
    {
        return array_values(array_map(
            fn (QuestionOption $o) => $o->id(),
            array_filter($this->options, fn (QuestionOption $o) => $o->isCorrect()),
        ));
    }

    public function id(): int
    {
        return $this->id;
    }

    public function text(): string
    {
        return $this->text;
    }

    public function type(): QuestionType
    {
        return $this->type;
    }

    public function points(): Points
    {
        return $this->points;
    }

    public function correctAnswer(): ?string
    {
        return $this->correctAnswer;
    }

    public function options(): array
    {
        return $this->options;
    }
}
