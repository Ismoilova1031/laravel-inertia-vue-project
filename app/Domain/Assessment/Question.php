<?php

namespace App\Domain\Assessment;

use App\Domain\Assessment\Exception\InvalidQuestionException;

final class Question
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $text,
        public readonly QuestionType $type,
        public readonly Points $points,
        public readonly ?string $correctAnswer,
        public readonly array $options
    ) {}

    public static function create(
        string $text,
        QuestionType $type,
        Points $points,
        ?string $correctAnswer,
        array $options
    ){
        $text = trim($text);

        if($text === ''){
            throw InvalidQuestionException::because('Savol matni bo\'sh bo\'lishi mumkin emas');
        }

        $correct = $correctAnswer !== null ? trim($correctAnswer) : null;
        $options = array_values($options);

        switch($type) {
            case QuestionType::SINGLE_CHOICE:
            case QuestionType::MULTIPLE_SELECT:
                if(count($options) < 2) {
                    throw InvalidQuestionException::because('Tanlov savoli kamida 2ta variat talab qiladi');
                }

                $correctCount = count(array_filter($options, fn (QuestionOption $o) => $o->isCorrect()));

                if($type === QuestionType::SINGLE_CHOICE && $correctCount !== 1){
                    throw InvalidQuestionException::because('Bitta tog\'ri javob belgilanadigan savol uchun faqat bitta tog\'ri javob bo\'lishi kerak');
                }

                if($type === QuestionType::MULTIPLE_SELECT && $correctCount < 1){
                    throw InvalidQuestionException::because('Ko\'p javobli savol uchun kamida bitta tog\'ri javob bo\'lishi kerak');
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

        return new self(null, $text, $type, $points, $correct, $options);
    }

    public static function reconstitute(
        int $id, 
        string $text,
        QuestionType $type,
        Points $points,
        ?string $correctAnswer,
        array $options
    ): self {
        return new self($id, $text, $type, $points, $correctAnswer, $options);
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

    public function id(): ?int { return $this->id; }
    public function text(): string { return $this->text; }
    public function type(): QuestionType { return $this->type; }
    public function points(): Points { return $this->points; }
    public function correctAnswer(): ?string { return $this->correctAnswer; }
    public function options(): array { return $this->options; }
}