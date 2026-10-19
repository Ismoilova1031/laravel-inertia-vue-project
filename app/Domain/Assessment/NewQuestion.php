<?php

namespace App\Domain\Assessment;

use App\Domain\Assessment\Exception\InvalidQuestionException;

final class NewQuestion
{
    private function __construct(
        private readonly string $text,
        private readonly QuestionType $type,
        private readonly Points $points,
        private readonly ?string $correctAnswer,
        private readonly array $options,
    ) {}

    public static function create(
        string $text,
        QuestionType $type,
        Points $points,
        ?string $correctAnswer,
        array $options,
    ): self {
        $text = trim($text);
        if ($text === '') {
            throw InvalidQuestionException::because('Savol matni bo\'sh bo\'lishi mumkin emas.');
        }

        $correct = $correctAnswer !== null ? trim($correctAnswer) : null;
        $options = array_values($options);

        switch ($type) {
            case QuestionType::SINGLE_CHOICE:
            case QuestionType::MULTIPLE_SELECT:
                if (count($options) < 2) {
                    throw InvalidQuestionException::because('Tanlov savoli kamida 2 ta variant talab qiladi.');
                }

                $correctCount = count(array_filter($options, fn (NewQuestionOption $o) => $o->isCorrect()));

                if ($type === QuestionType::SINGLE_CHOICE && $correctCount !== 1) {
                    throw InvalidQuestionException::because('Single choice savolda aynan bitta to\'g\'ri variant bo\'lishi kerak.');
                }
                if ($type === QuestionType::MULTIPLE_SELECT && $correctCount < 1) {
                    throw InvalidQuestionException::because('Multiple select savolda kamida bitta to\'g\'ri variant bo\'lishi kerak.');
                }

                $correct = null;
                break;

            case QuestionType::SHORT_ANSWER:
                if ($correct === null || $correct === '') {
                    throw InvalidQuestionException::because('Short answer savol uchun to\'g\'ri javob kiritilishi kerak.');
                }
                $options = [];
                break;

            case QuestionType::OPEN_ENDED:
                $correct = null;
                $options = [];
                break;
        }

        return new self($text, $type, $points, $correct, $options);
    }

    public function signature(): array
    {
        return [
            $this->text,
            $this->type->value,
            $this->points->value(),
            $this->correctAnswer,
            array_map(fn (NewQuestionOption $o) => [$o->text(), $o->isCorrect()], $this->options),
        ];
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
